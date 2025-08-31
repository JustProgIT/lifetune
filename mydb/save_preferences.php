<?php
// Not in use, probably will be removed

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $userId               = $_POST["userId"] ?? null;
    $ageGroup             = $_POST["ageGroup"] ?? null;
    $occupation           = $_POST["occupation"] ?? null;
    $livingSituation      = $_POST["livingSituation"] ?? null;
    $relationshipStatus   = $_POST["relationshipStatus"] ?? null;
    $personalityType      = $_POST["personalityType"] ?? null;
    $coachingStyle        = $_POST["coachingStyle"] ?? null;
    $stressRelievers      = $_POST["stressRelievers"] ?? null;
    $problemSolvingMethod = $_POST["problemSolvingMethod"] ?? null;



    $conn = new mysqli("localhost", "root", "", "ailifecoach");

    if ($conn->connect_error) {
        echo json_encode(["success" => false, "error" => $conn->connect_error]);
        exit;
    }

    $stmt = $conn->prepare("
        INSERT INTO preferences (
            user_id, ageGroup, occupation, livingSituation, relationshipStatus, personalityType,
            coachingStyle, stressRelievers, problemSolvingMethod, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ON DUPLICATE KEY UPDATE
            ageGroup = VALUES(ageGroup),
            occupation = VALUES(occupation),
            livingSituation = VALUES(livingSituation),
            relationshipStatus = VALUES(relationshipStatus),
            personalityType = VALUES(personalityType),
            coachingStyle = VALUES(coachingStyle),
            stressRelievers = VALUES(stressRelievers),
            problemSolvingMethod = VALUES(problemSolvingMethod),
            updated_at = NOW()
    "); // <-- Fixed closing

    $stmt->bind_param(
        "sssssssss",
        $userId,
        $ageGroup,
        $occupation,
        $livingSituation,
        $relationshipStatus,
        $personalityType,
        $coachingStyle,
        $stressRelievers,
        $problemSolvingMethod
    );

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Preferences saved successfully!"]);
    } else {
        echo json_encode([
            "success" => false,
            "error" => $stmt->error,
            "errno" => $stmt->errno
        ]);
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
