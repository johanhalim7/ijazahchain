// SPDX-License-Identifier: MIT
pragma solidity ^0.8.24;

contract IjazahChain {
    error Unauthorized();
    error AlreadyExists();
    error NotFound();
    error AlreadyRevoked();
    error InvalidHash();
    error InvalidSignature(uint256 index);
    error InvalidWorkflow();

    enum Status {
        None,
        Uploaded,
        Revoked
    }

    struct Diploma {
        string diplomaHash;
        string nomorIjazah;
        address[] signers;
        address admin;
        Status status;
        uint256 issuedAt;
        uint256 revokedAt;
    }

    address public immutable adminWallet;

    bytes32 private constant DIPLOMA_APPROVAL_TYPEHASH = keccak256("DiplomaApproval(string action,string nomorIjazah,string diplomaHash,uint256 version)");

    mapping(bytes32 => Diploma) private diplomas;
    
    // Dynamic Workflow Management
    mapping(uint256 => address[]) public workflows;
    
    event AdminApproved(string indexed nomorIjazah, string diplomaHash, address indexed admin);
    event DiplomaUploaded(string indexed nomorIjazah, string diplomaHash, address indexed admin);
    event DiplomaRevoked(string indexed nomorIjazah, string diplomaHash, address indexed admin);
    event WorkflowUpdated(uint256 indexed workflowId, address[] signers);

    modifier onlyAdmin() {
        if (msg.sender != adminWallet) revert Unauthorized();
        _;
    }

    // Now we only lock the Admin wallet on deployment.
    // The other signers will be configured dynamically via setWorkflow.
    constructor(address _adminWallet) {
        adminWallet = _adminWallet;
    }

    function setWorkflow(uint256 workflowId, address[] calldata _signers) external onlyAdmin {
        workflows[workflowId] = _signers;
        emit WorkflowUpdated(workflowId, _signers);
    }

    function _domainSeparatorV4() internal view returns (bytes32) {
        return keccak256(
            abi.encode(
                keccak256("EIP712Domain(string name,string version,uint256 chainId,address verifyingContract)"),
                keccak256(bytes("IjazahChain")),
                keccak256(bytes("1")),
                block.chainid,
                address(this)
            )
        );
    }

    function verifySignature(
        string memory action,
        string memory nomorIjazah,
        string memory diplomaHash,
        uint256 version,
        bytes memory signature,
        address expectedSigner
    ) internal view returns (bool) {
        bytes32 structHash = keccak256(abi.encode(
            DIPLOMA_APPROVAL_TYPEHASH,
            keccak256(bytes(action)),
            keccak256(bytes(nomorIjazah)),
            keccak256(bytes(diplomaHash)),
            version
        ));
        bytes32 digest = keccak256(abi.encodePacked("\x19\x01", _domainSeparatorV4(), structHash));
        
        if (signature.length != 65) return false;
        bytes32 r; bytes32 s; uint8 v;
        assembly {
            r := mload(add(signature, 0x20))
            s := mload(add(signature, 0x40))
            v := byte(0, mload(add(signature, 0x60)))
        }
        if (v < 27) v += 27;
        if (v != 27 && v != 28) return false;
        
        address signer = ecrecover(digest, v, r, s);
        return signer == expectedSigner;
    }

    function approveAdmin(
        string calldata nomorIjazah, 
        string calldata diplomaHash,
        uint256 version,
        uint256 workflowId,
        bytes[] calldata signatures
    ) external onlyAdmin {
        if (bytes(diplomaHash).length == 0) revert InvalidHash();
        bytes32 key = keccak256(bytes(nomorIjazah));
        if (diplomas[key].status != Status.None) revert AlreadyExists();

        address[] memory requiredSigners = workflows[workflowId];
        if (requiredSigners.length == 0) revert InvalidWorkflow();
        if (signatures.length != requiredSigners.length) revert InvalidWorkflow();

        // Verifikasi semua tanda tangan secara dinamis berdasarkan workflow
        for (uint256 i = 0; i < requiredSigners.length; i++) {
            if (!verifySignature("approve", nomorIjazah, diplomaHash, version, signatures[i], requiredSigners[i])) {
                revert InvalidSignature(i);
            }
        }

        diplomas[key] = Diploma({
            diplomaHash: diplomaHash,
            nomorIjazah: nomorIjazah,
            signers: requiredSigners,
            admin: msg.sender,
            status: Status.Uploaded,
            issuedAt: block.timestamp,
            revokedAt: 0
        });

        emit AdminApproved(nomorIjazah, diplomaHash, msg.sender);
        emit DiplomaUploaded(nomorIjazah, diplomaHash, msg.sender);
    }

    function verify(string calldata nomorIjazah, string calldata diplomaHash) external view returns (bool valid, bool revoked) {
        Diploma storage diploma = diplomas[keccak256(bytes(nomorIjazah))];
        if (diploma.status == Status.None || keccak256(bytes(diploma.diplomaHash)) != keccak256(bytes(diplomaHash))) return (false, false);
        if (diploma.status == Status.Revoked) return (false, true);
        return (true, false);
    }

    function getDiploma(string calldata nomorIjazah) external view returns (Diploma memory) {
        Diploma memory diploma = diplomas[keccak256(bytes(nomorIjazah))];
        if (diploma.status == Status.None) revert NotFound();
        return diploma;
    }

    function revoke(string calldata nomorIjazah, string calldata diplomaHash) external onlyAdmin {
        bytes32 key = keccak256(bytes(nomorIjazah));
        Diploma storage diploma = diplomas[key];
        if (diploma.status == Status.None) revert NotFound();
        if (diploma.status == Status.Revoked) revert AlreadyRevoked();
        if (keccak256(bytes(diploma.diplomaHash)) != keccak256(bytes(diplomaHash))) revert InvalidHash();

        diploma.status = Status.Revoked;
        diploma.revokedAt = block.timestamp;

        emit DiplomaRevoked(nomorIjazah, diplomaHash, msg.sender);
    }
}
