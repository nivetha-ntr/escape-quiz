
<?php
/*
|--------------------------------------------------------------------------
| ESCAPE THE QUIZ ROOM
| PHP + PostgreSQL + HTML + CSS + JavaScript
| One-file application: index.php
|--------------------------------------------------------------------------
*/

session_start();
date_default_timezone_set('Asia/Kolkata');

/* 1. DATABASE CONFIGURATION */
$dbHost = '127.0.0.1';
$dbPort = '5432';
$dbName = 'webgis';
$dbUser = 'postgres';
$dbPass = 'ntr07'; // Replace with your PostgreSQL password

$competitionCode = 'MAP15';
$organizerPin = '2468';
$questionSeconds = 25;

try {
    $pdo = new PDO(
        "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName",
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit(
        '<h2>Database connection failed</h2>' .
        '<p>Check the PostgreSQL host, database name, username, password, ' .
        'and PHP pdo_pgsql extension.</p>'
    );
}

/* 2. EXACTLY 15 WEBGIS QUESTIONS */
$questions = [
    [
        'topic' => 'GIS Fundamentals',
        'question' => 'What does GIS stand for?',
        'options' => [
            'Geographic Information System',
            'Global Internet Service',
            'Geological Imaging Software',
            'Geographic Internet Server'
        ],
        'answer' => 0,
        'explanation' => 'GIS is used to capture, manage, analyse and display geographically referenced data.'
    ],
    [
        'topic' => 'Remote Sensing',
        'question' => 'Which technology collects information about Earth without direct physical contact?',
        'options' => [
            'Levelling',
            'Remote sensing',
            'Chain surveying',
            'Ground levelling'
        ],
        'answer' => 1,
        'explanation' => 'Remote sensing collects information using sensors mounted on satellites, aircraft and other platforms.'
    ],
    [
        'topic' => 'Coordinate Systems',
        'question' => 'Which coordinate system commonly uses latitude and longitude?',
        'options' => [
            'Geographic coordinate system',
            'Local building grid only',
            'Pixel coordinate system',
            'Attribute table system'
        ],
        'answer' => 0,
        'explanation' => 'A geographic coordinate system represents locations using angular coordinates such as latitude and longitude.'
    ],
    [
        'topic' => 'Vector Data',
        'question' => 'Which vector geometry is most suitable for representing a road?',
        'options' => [
            'Point',
            'Polygon',
            'Line',
            'Raster cell'
        ],
        'answer' => 2,
        'explanation' => 'Roads are commonly represented as line features in vector GIS.'
    ],
    [
        'topic' => 'Raster Data',
        'question' => 'A satellite image represented by a regular grid of cells is an example of what?',
        'options' => [
            'Raster data',
            'Point data only',
            'Topology rules',
            'A relational key'
        ],
        'answer' => 0,
        'explanation' => 'Raster data represents space using cells or pixels, each of which stores a value.'
    ],
    [
        'topic' => 'GPS',
        'question' => 'What is the primary purpose of GPS?',
        'options' => [
            'Editing polygons',
            'Determining geographic position',
            'Classifying land cover automatically',
            'Creating database tables'
        ],
        'answer' => 1,
        'explanation' => 'GPS uses satellite signals to estimate position, and can also provide timing and velocity.'
    ],
    [
        'topic' => 'Spatial Analysis',
        'question' => 'Which operation creates a zone at a specified distance around a feature?',
        'options' => [
            'Buffer',
            'Geocoding',
            'Digitising',
            'Georeferencing'
        ],
        'answer' => 0,
        'explanation' => 'A buffer creates a zone around a point, line or polygon at a specified distance.'
    ],
    [
        'topic' => 'Map Projections',
        'question' => 'Why are map projections used?',
        'options' => [
            'To convert a curved Earth representation onto a flat map',
            'To increase the number of GPS satellites',
            'To remove every spatial error',
            'To convert all vector data into text'
        ],
        'answer' => 0,
        'explanation' => 'Map projections transform positions on a curved Earth model onto a flat surface, introducing some distortion.'
    ],
    [
        'topic' => 'WebGIS',
        'question' => 'Which technology is commonly used to display an interactive web map?',
        'options' => [
            'Leaflet',
            'Microsoft Paint only',
            'Windows Calculator',
            'Notepad only'
        ],
        'answer' => 0,
        'explanation' => 'Leaflet is a JavaScript library for interactive web maps.'
    ],
    [
        'topic' => 'Spatial Databases',
        'question' => 'Which PostgreSQL extension adds spatial data types and spatial functions?',
        'options' => [
            'PostGIS',
            'PDO_HTML',
            'GeoCSS',
            'MapPaint'
        ],
        'answer' => 0,
        'explanation' => 'PostGIS adds spatial types, indexing and functions to PostgreSQL.'
    ],
    [
        'topic' => 'Remote Sensing',
        'question' => 'Which index is commonly used to assess vegetation greenness?',
        'options' => [
            'NDVI',
            'SQL',
            'HTTP',
            'JPEG'
        ],
        'answer' => 0,
        'explanation' => 'NDVI uses near-infrared and red reflectance to indicate vegetation greenness.'
    ],
    [
        'topic' => 'Spatial Queries',
        'question' => 'Which spatial operation identifies features that intersect another geometry?',
        'options' => [
            'ST_Intersects',
            'ST_TranslateText',
            'COUNT_WORDS',
            'HTML_SELECT'
        ],
        'answer' => 0,
        'explanation' => 'PostGIS ST_Intersects tests whether two geometries share any spatial area or boundary points.'
    ],
    [
        'topic' => 'Cartography',
        'question' => 'What does a map scale describe?',
        'options' => [
            'The relationship between map distance and ground distance',
            'The number of colours in a map',
            'The number of database tables',
            'The brightness of a satellite sensor'
        ],
        'answer' => 0,
        'explanation' => 'Map scale expresses the relationship between a distance on the map and the corresponding ground distance.'
    ],
    [
        'topic' => 'GIS Data',
        'question' => 'Which GIS data type is especially suitable for storing elevation values across a continuous surface?',
        'options' => [
            'Raster',
            'A list of place names only',
            'A single text field',
            'A web page title'
        ],
        'answer' => 0,
        'explanation' => 'A raster digital elevation model stores elevation values in a grid of cells.'
    ],
    [
        'topic' => 'Spatial Analysis',
        'question' => 'Which method combines multiple weighted criteria to create a suitability map?',
        'options' => [
            'Weighted overlay',
            'Changing the browser theme',
            'Sorting names alphabetically',
            'Compressing an image'
        ],
        'answer' => 0,
        'explanation' => 'Weighted overlay combines criterion scores and weights to calculate relative suitability.'
    ]
];

/* 3. CREATE QUIZ TABLES AUTOMATICALLY */
$pdo->exec("
    CREATE TABLE IF NOT EXISTS eq_participants (
        id BIGSERIAL PRIMARY KEY,
        player_name VARCHAR(80) NOT NULL,
        join_code VARCHAR(20) NOT NULL,
        current_question INTEGER NOT NULL DEFAULT 0,
        score INTEGER NOT NULL DEFAULT 0,
        status VARCHAR(20) NOT NULL DEFAULT 'playing',
        current_started_at TIMESTAMPTZ,
        last_answered_question INTEGER,
        last_was_correct BOOLEAN,
        last_selected_answer INTEGER,
        joined_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
        updated_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
    )
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS eq_answers (
        id BIGSERIAL PRIMARY KEY,
        participant_id BIGINT NOT NULL
            REFERENCES eq_participants(id) ON DELETE CASCADE,
        question_number INTEGER NOT NULL,
        selected_answer INTEGER,
        correct BOOLEAN NOT NULL DEFAULT FALSE,
        timed_out BOOLEAN NOT NULL DEFAULT FALSE,
        points INTEGER NOT NULL DEFAULT 0,
        answered_at TIMESTAMPTZ NOT NULL DEFAULT NOW(),
        UNIQUE(participant_id, question_number)
    )
");

$pdo->exec("
    CREATE INDEX IF NOT EXISTS eq_participants_status_idx
    ON eq_participants(status)
");

$pdo->exec("
    CREATE INDEX IF NOT EXISTS eq_answers_participant_idx
    ON eq_answers(participant_id)
");

/* 4. COMMON FUNCTIONS */
function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

function requestData() {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : $_POST;
}

function getParticipant(PDO $pdo) {
    if (empty($_SESSION['eq_player_id'])) {
        return null;
    }

    $stmt = $pdo->prepare(
        "SELECT * FROM eq_participants WHERE id = ?"
    );
    $stmt->execute([$_SESSION['eq_player_id']]);

    return $stmt->fetch() ?: null;
}

function remainingSeconds($startedAt, $seconds) {
    if (!$startedAt) {
        return 0;
    }

    $start = strtotime($startedAt);
    if (!$start) {
        return 0;
    }

    return max(0, $seconds - (time() - $start));
}

/*
 * A question is recorded as timed out only when its server-side
 * deadline has passed. The client cannot award itself points.
 */
function recordTimeout(
    PDO $pdo,
    array $player,
    array $questions,
    int $seconds
) {
    $qNum = (int)$player['current_question'];

    if ($player['status'] !== 'playing'
        || $qNum >= count($questions)
        || !$player['current_started_at']) {
        return $player;
    }

    if (remainingSeconds(
        $player['current_started_at'],
        $seconds
    ) > 0) {
        return $player;
    }

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("
            INSERT INTO eq_answers
                (participant_id, question_number, selected_answer,
                 correct, timed_out, points)
            VALUES (?, ?, NULL, FALSE, TRUE, 0)
            ON CONFLICT (participant_id, question_number) DO NOTHING
        ");

        $stmt->execute([$player['id'], $qNum]);

        $stmt = $pdo->prepare("
            UPDATE eq_participants
            SET last_answered_question = ?,
                last_was_correct = FALSE,
                last_selected_answer = NULL,
                updated_at = NOW()
            WHERE id = ?
        ");

        $stmt->execute([$qNum, $player['id']]);

        $pdo->commit();
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }

    $stmt = $pdo->prepare(
        "SELECT * FROM eq_participants WHERE id = ?"
    );
    $stmt->execute([$player['id']]);

    return $stmt->fetch();
}

/* 5. JSON API */
if (isset($_GET['api'])) {
    $action = $_GET['api'];
    $data = requestData();

    try {
        /* JOIN */
        if ($action === 'join') {
            $name = trim($data['name'] ?? '');
            $code = strtoupper(trim($data['code'] ?? ''));

            if ($name === '' || mb_strlen($name) > 80) {
                jsonResponse([
                    'ok' => false,
                    'error' => 'Enter a name of 1–80 characters.'
                ], 422);
            }

            if (!hash_equals($competitionCode, $code)) {
                jsonResponse([
                    'ok' => false,
                    'error' => 'Incorrect competition code.'
                ], 403);
            }

            $stmt = $pdo->prepare("
                INSERT INTO eq_participants
                    (player_name, join_code, current_question,
                     score, status, current_started_at)
                VALUES (?, ?, 0, 0, 'playing', NOW())
                RETURNING id
            ");

            $stmt->execute([$name, $code]);
            $id = $stmt->fetchColumn();

            session_regenerate_id(true);
            $_SESSION['eq_player_id'] = (int)$id;
            unset($_SESSION['eq_organizer']);

            jsonResponse(['ok' => true]);
        }

        /* ORGANIZER LOGIN */
        if ($action === 'organizer_login') {
            $pin = (string)($data['pin'] ?? '');

            if (!hash_equals($organizerPin, $pin)) {
                jsonResponse([
                    'ok' => false,
                    'error' => 'Incorrect organizer PIN.'
                ], 403);
            }

            session_regenerate_id(true);
            $_SESSION['eq_organizer'] = true;
            unset($_SESSION['eq_player_id']);

            jsonResponse(['ok' => true]);
        }

        /* ORGANIZER LOGOUT */
        if ($action === 'organizer_logout') {
            unset($_SESSION['eq_organizer']);
            jsonResponse(['ok' => true]);
        }

        /* ORGANIZER DASHBOARD DATA */
        if ($action === 'organizer_data') {
            if (empty($_SESSION['eq_organizer'])) {
                jsonResponse([
                    'ok' => false,
                    'error' => 'Organizer login required.'
                ], 403);
            }

            $stmt = $pdo->query("
                SELECT
                    p.id,
                    p.player_name,
                    p.current_question,
                    p.score,
                    p.status,
                    p.joined_at,
                    p.updated_at,
                    COUNT(a.id) AS answered_count,
                    COUNT(a.id) FILTER (WHERE a.correct) AS correct_count,
                    COUNT(a.id) FILTER (WHERE a.timed_out) AS timeout_count
                FROM eq_participants p
                LEFT JOIN eq_answers a ON a.participant_id = p.id
                GROUP BY p.id
                ORDER BY p.score DESC, p.joined_at ASC
            ");

            $players = $stmt->fetchAll();

            foreach ($players as &$p) {
                $p['current_question'] = (int)$p['current_question'];
                $p['score'] = (int)$p['score'];
                $p['answered_count'] = (int)$p['answered_count'];
                $p['correct_count'] = (int)$p['correct_count'];
                $p['timeout_count'] = (int)$p['timeout_count'];

                $p['stage'] = $p['status'] === 'finished'
                    ? 'Completed'
                    : 'Question ' . min(
                        $p['current_question'] + 1,
                        count($questions)
                    ) . ' of ' . count($questions);
            }
            unset($p);

            jsonResponse([
                'ok' => true,
                'players' => $players,
                'total' => count($players),
                'completed' => count(array_filter(
                    $players,
                    fn($p) => $p['status'] === 'finished'
                )),
                'playing' => count(array_filter(
                    $players,
                    fn($p) => $p['status'] === 'playing'
                ))
            ]);
        }

        /* RESET COMPETITION */
        if ($action === 'organizer_reset') {
            if (empty($_SESSION['eq_organizer'])) {
                jsonResponse([
                    'ok' => false,
                    'error' => 'Organizer login required.'
                ], 403);
            }

            $pdo->beginTransaction();

            try {
                $pdo->exec("DELETE FROM eq_answers");
                $pdo->exec("DELETE FROM eq_participants");
                $pdo->exec("
                    ALTER SEQUENCE eq_participants_id_seq RESTART WITH 1
                ");
                $pdo->exec("
                    ALTER SEQUENCE eq_answers_id_seq RESTART WITH 1
                ");

                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack();
                throw $e;
            }

            jsonResponse(['ok' => true]);
        }

        /* PLAYER ACTIONS REQUIRE A JOINED PARTICIPANT */
        $player = getParticipant($pdo);

        if (!$player) {
            jsonResponse([
                'ok' => false,
                'error' => 'Join the competition first.'
            ], 401);
        }

        /* GET CURRENT QUESTION / FEEDBACK / RESULT */
        if ($action === 'state') {
            $player = recordTimeout(
                $pdo, $player, $questions, $questionSeconds
            );

            $qNum = (int)$player['current_question'];

            if ($player['status'] === 'finished'
                || $qNum >= count($questions)) {

                $stmt = $pdo->prepare("
                    SELECT
                        COUNT(*) FILTER (WHERE correct) AS correct_count,
                        COUNT(*) FILTER (WHERE timed_out) AS timeout_count
                    FROM eq_answers
                    WHERE participant_id = ?
                ");
                $stmt->execute([$player['id']]);
                $stats = $stmt->fetch();

                jsonResponse([
                    'ok' => true,
                    'screen' => 'finished',
                    'name' => $player['player_name'],
                    'score' => (int)$player['score'],
                    'correct_count' => (int)$stats['correct_count'],
                    'timeout_count' => (int)$stats['timeout_count'],
                    'total' => count($questions)
                ]);
            }

            $stmt = $pdo->prepare("
                SELECT * FROM eq_answers
                WHERE participant_id = ? AND question_number = ?
            ");
            $stmt->execute([$player['id'], $qNum]);
            $answerRow = $stmt->fetch();

            if ($answerRow) {
                $q = $questions[$qNum];

                jsonResponse([
                    'ok' => true,
                    'screen' => 'feedback',
                    'question_number' => $qNum + 1,
                    'total' => count($questions),
                    'score' => (int)$player['score'],
                    'correct' => (bool)$answerRow['correct'],
                    'timed_out' => (bool)$answerRow['timed_out'],
                    'selected_answer' => $answerRow['selected_answer'] === null
                        ? null
                        : (int)$answerRow['selected_answer'],
                    'correct_answer' => $q['answer'],
                    'explanation' => $q['explanation'],
                    'topic' => $q['topic']
                ]);
            }

            $q = $questions[$qNum];

            jsonResponse([
                'ok' => true,
                'screen' => 'question',
                'name' => $player['player_name'],
                'question_number' => $qNum + 1,
                'total' => count($questions),
                'score' => (int)$player['score'],
                'topic' => $q['topic'],
                'question' => $q['question'],
                'options' => $q['options'],
                'remaining' => remainingSeconds(
                    $player['current_started_at'],
                    $questionSeconds
                )
            ]);
        }

        /* SUBMIT ANSWER */
        if ($action === 'answer') {
            $qNum = (int)$player['current_question'];

            if ($player['status'] !== 'playing'
                || $qNum >= count($questions)) {
                jsonResponse([
                    'ok' => false,
                    'error' => 'This quiz has already ended.'
                ], 409);
            }

            $player = recordTimeout(
                $pdo, $player, $questions, $questionSeconds
            );

            $stmt = $pdo->prepare("
                SELECT id FROM eq_answers
                WHERE participant_id = ? AND question_number = ?
            ");
            $stmt->execute([$player['id'], $qNum]);

            if ($stmt->fetch()) {
                jsonResponse([
                    'ok' => false,
                    'error' => 'This question has already been submitted.'
                ], 409);
            }

            $selected = filter_var(
                $data['answer'] ?? null,
                FILTER_VALIDATE_INT
            );

            if ($selected === false
                || $selected < 0
                || $selected > 3) {
                jsonResponse([
                    'ok' => false,
                    'error' => 'Choose one of the four options.'
                ], 422);
            }

            if (remainingSeconds(
                $player['current_started_at'],
                $questionSeconds
            ) <= 0) {
                $player = recordTimeout(
                    $pdo, $player, $questions, $questionSeconds
                );

                jsonResponse([
                    'ok' => false,
                    'error' => 'Time expired. Your answer was not accepted.'
                ], 409);
            }

            $correct = ($selected === $questions[$qNum]['answer']);
            $points = $correct ? 10 : 0;

            $pdo->beginTransaction();

            try {
                $stmt = $pdo->prepare("
                    INSERT INTO eq_answers
                        (participant_id, question_number, selected_answer,
                         correct, timed_out, points)
                    VALUES (?, ?, ?, ?, FALSE, ?)
                ");

                $stmt->execute([
                    $player['id'], $qNum, $selected, $correct, $points
                ]);

                $stmt = $pdo->prepare("
                    UPDATE eq_participants
                    SET score = score + ?,
                        last_answered_question = ?,
                        last_was_correct = ?,
                        last_selected_answer = ?,
                        updated_at = NOW()
                    WHERE id = ?
                ");

                $stmt->execute([
                    $points, $qNum, $correct, $selected, $player['id']
                ]);

                $pdo->commit();
            } catch (Throwable $e) {
                $pdo->rollBack();
                throw $e;
            }

            jsonResponse([
                'ok' => true,
                'correct' => $correct,
                'points' => $points
            ]);
        }

        /* CONTINUE AFTER FEEDBACK */
        if ($action === 'next') {
            $qNum = (int)$player['current_question'];

            $stmt = $pdo->prepare("
                SELECT id FROM eq_answers
                WHERE participant_id = ? AND question_number = ?
            ");
            $stmt->execute([$player['id'], $qNum]);

            if (!$stmt->fetch()) {
                jsonResponse([
                    'ok' => false,
                    'error' => 'Submit your answer first.'
                ], 409);
            }

            $nextQ = $qNum + 1;

            if ($nextQ >= count($questions)) {
                $stmt = $pdo->prepare("
                    UPDATE eq_participants
                    SET current_question = ?,
                        status = 'finished',
                        updated_at = NOW()
                    WHERE id = ?
                ");

                $stmt->execute([$nextQ, $player['id']]);
            } else {
                $stmt = $pdo->prepare("
                    UPDATE eq_participants
                    SET current_question = ?,
                        current_started_at = NOW(),
                        updated_at = NOW()
                    WHERE id = ?
                ");

                $stmt->execute([$nextQ, $player['id']]);
            }

            jsonResponse(['ok' => true]);
        }

        jsonResponse([
            'ok' => false,
            'error' => 'Unknown action.'
        ], 404);

    } catch (Throwable $e) {
        error_log('Escape Quiz error: ' . $e->getMessage());

        jsonResponse([
            'ok' => false,
            'error' => 'A server error occurred. Check the PHP error log.'
        ], 500);
    }
}

/* 6. PAGE ROUTING */
$isOrganizerPage = isset($_GET['organizer']);
$isOrganizer = !empty($_SESSION['eq_organizer']);
$player = getParticipant($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Escape the Quiz Room | WebGIS Challenge</title>
<style>
:root {
    --bg: #080d1d;
    --panel: #111a30;
    --panel2: #17233c;
    --text: #f4f7ff;
    --muted: #9caac5;
    --accent: #8b7cff;
    --cyan: #5be7e8;
    --green: #4ee0a0;
    --red: #ff708b;
    --border: rgba(255,255,255,.11);
}
* { box-sizing: border-box; }
body {
    margin: 0;
    min-height: 100vh;
    background:
        radial-gradient(ellipse at top left, #20275a 0, transparent 42%),
        radial-gradient(ellipse at bottom right, #113b4a 0, transparent 40%),
        var(--bg);
    color: var(--text);
    font-family: Inter, Segoe UI, Arial, sans-serif;
}
button, input { font: inherit; }
button {
    cursor: pointer;
    border: 0;
    border-radius: 12px;
    padding: 13px 18px;
    font-weight: 800;
    transition: transform .18s, opacity .18s;
}
button:hover { transform: translateY(-2px); }
button:disabled { cursor: not-allowed; opacity: .45; }
.primary {
    background: linear-gradient(110deg, var(--accent), #6a9aff);
    color: white;
}
.secondary {
    color: var(--text);
    background: #263452;
    border: 1px solid var(--border);
}
.danger { background: #61263a; color: white; }
.wrap { width: min(1100px, 94%); margin: auto; }
.topbar {
    display: flex; align-items: center; justify-content: space-between;
    gap: 15px; padding: 22px 0;
}
.brand { font-size: 17px; font-weight: 900; letter-spacing: .4px; }
.brand span { color: var(--cyan); }
.pill {
    padding: 7px 11px; border: 1px solid var(--border);
    border-radius: 99px; color: var(--muted); font-size: 12px;
}
.hero { text-align: center; padding: 35px 0 22px; }
.eyebrow {
    color: var(--cyan); font-size: 12px; font-weight: 900;
    text-transform: uppercase; letter-spacing: 2px;
}
h1 { font-size: clamp(32px, 6vw, 58px); line-height: 1.05; margin: 14px 0; }
.gradient {
    background: linear-gradient(100deg, #b2a5ff, #5be7e8);
    color: transparent; background-clip: text;
}
.sub { color: var(--muted); line-height: 1.7; }
.card {
    background: linear-gradient(145deg, rgba(24,35,61,.97), rgba(14,22,40,.97));
    border: 1px solid var(--border);
    border-radius: 22px; padding: 24px;
    box-shadow: 0 20px 65px rgba(0,0,0,.15);
}
.join-card { width: min(480px, 100%); margin: 24px auto; }
label { display: block; font-size: 13px; color: var(--muted); margin: 16px 0 8px; }
input {
    width: 100%; padding: 14px; border-radius: 12px;
    border: 1px solid var(--border); background: #0b1326; color: white;
    outline: none;
}
input:focus { border-color: var(--accent); }
.full { width: 100%; margin-top: 18px; }
.features {
    display: grid; grid-template-columns: repeat(3,1fr);
    gap: 12px; margin: 22px 0 35px;
}
.feature {
    padding: 17px; background: rgba(255,255,255,.035);
    border: 1px solid var(--border); border-radius: 15px;
}
.feature strong { display: block; margin-bottom: 6px; }
.feature small { color: var(--muted); line-height: 1.5; }
.game { width: min(760px, 100%); margin: 22px auto 40px; }
.game-meta {
    display: flex; justify-content: space-between; flex-wrap: wrap;
    gap: 10px; margin-bottom: 16px;
}
.stat { background: #0b1326; padding: 11px 14px; border-radius: 12px; }
.stat small { display: block; color: var(--muted); font-size: 11px; }
.stat strong { display: block; margin-top: 4px; }
.progress {
    height: 7px; background: #263452; border-radius: 99px;
    overflow: hidden; margin: 16px 0 23px;
}
.progress div {
    height: 100%; background: linear-gradient(90deg, var(--accent), var(--cyan));
    transition: width .35s;
}
.topic {
    display: inline-block; padding: 7px 10px; border-radius: 8px;
    background: #25264b; color: #c8c2ff; font-size: 12px; font-weight: 800;
}
.question { font-size: clamp(22px,4vw,31px); line-height: 1.3; margin: 18px 0; }
.options { display: grid; gap: 11px; }
.option {
    display: flex; align-items: center; gap: 12px; width: 100%;
    text-align: left; background: #121e35; color: white;
    border: 1px solid var(--border); padding: 16px; border-radius: 14px;
}
.option .letter {
    flex: 0 0 32px; height: 32px; border-radius: 9px;
    display: grid; place-items: center; background: #263452;
}
.option.selected { border-color: var(--cyan); background: #153445; }
.option.correct { border-color: var(--green); background: #143c36; }
.option.wrong { border-color: var(--red); background: #442334; }
.row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 20px; }
.row button { flex: 1; }
.timer {
    width: 62px; height: 62px; display: grid; place-items: center;
    border-radius: 50%; border: 4px solid var(--accent); font-weight: 900;
}
.timer.urgent { border-color: var(--red); color: var(--red); animation: pulse 1s infinite; }
.feedback { text-align: center; padding: 20px 0; }
.feedback .emoji { font-size: 56px; animation: pop .4s ease-out; }
.bigscore { font-size: 54px; font-weight: 900; color: var(--cyan); }
.hidden { display: none !important; }
.error { color: var(--red); margin-top: 12px; font-size: 13px; }
.muted { color: var(--muted); }
.center { text-align: center; }
.table-wrap { overflow-x: auto; }
table { border-collapse: collapse; width: 100%; min-width: 650px; }
th, td { padding: 13px 12px; text-align: left; border-bottom: 1px solid var(--border); }
th { color: var(--muted); font-size: 12px; text-transform: uppercase; }
td { font-size: 13px; }
.badge {
    display: inline-block; padding: 6px 9px; border-radius: 8px;
    background: #263452; font-size: 11px;
}
.badge.done { color: var(--green); background: #143c36; }
.badge.live { color: var(--cyan); background: #123744; }
.stats {
    display: grid; grid-template-columns: repeat(3,1fr); gap: 12px; margin: 18px 0;
}
.statbox { padding: 20px; background: #0b1326; border-radius: 15px; }
.statbox strong { font-size: 28px; display: block; }
.statbox span { color: var(--muted); font-size: 12px; }
footer { text-align: center; color: var(--muted); font-size: 12px; padding: 25px 0; }
@keyframes pulse { 50% { transform: scale(1.05); } }
@keyframes pop { from { transform: scale(.5); opacity: .2; } to { transform: scale(1); opacity: 1; } }
@media(max-width:600px) {
    .features { grid-template-columns: 1fr; }
    .stats { grid-template-columns: 1fr; }
    .card { padding: 18px; }
    .topbar { align-items: flex-start; }
}
</style>
</head>
<body>
<div class="wrap">
    <header class="topbar">
        <div class="brand">MAP<span>QUEST</span> / WEBGIS</div>
        <div class="pill">INDIVIDUAL CHALLENGE · 15 QUESTIONS</div>
    </header>

<?php if ($isOrganizerPage): ?>

    <section id="organizerLogin" class="join-card card <?php echo $isOrganizer ? 'hidden' : ''; ?>">
        <div class="eyebrow">Organizer access</div>
        <h2>Control Room</h2>
        <p class="sub">Sign in to monitor participants, scores and stages.</p>
        <form id="organizerForm">
            <label for="pin">Organizer PIN</label>
            <input id="pin" type="password" required placeholder="Enter organizer PIN">
            <button class="primary full">Open dashboard</button>
            <div id="loginError" class="error"></div>
        </form>
    </section>

    <section id="dashboard" class="<?php echo $isOrganizer ? '' : 'hidden'; ?>">
        <div class="hero" style="text-align:left">
            <div class="eyebrow">Live competition monitor</div>
            <h1>Organizer <span class="gradient">Control Room</span></h1>
            <p class="sub">Track every participant's stage, score and progress.</p>
        </div>

        <div class="stats">
            <div class="statbox"><strong id="totalPlayers">0</strong><span>Participants</span></div>
            <div class="statbox"><strong id="playingPlayers">0</strong><span>Currently playing</span></div>
            <div class="statbox"><strong id="completedPlayers">0</strong><span>Completed</span></div>
        </div>

        <div class="card">
            <div class="game-meta">
                <div><h2 style="margin:0 0 6px">Participant tracker</h2>
                <span class="muted">Refreshes automatically every 4 seconds</span></div>
                <div class="row" style="margin:0">
                    <button class="secondary" onclick="loadDashboard()">Refresh</button>
                    <button class="danger" onclick="resetCompetition()">Reset competition</button>
                    <button class="secondary" onclick="logoutOrganizer()">Log out</button>
                </div>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th><th>Participant</th><th>Current stage</th>
                            <th>Score</th><th>Correct</th><th>Timeouts</th><th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="playersTable">
                        <tr><td colspan="7">Loading participants...</td></tr>
                    </tbody>
                </table>
            </div>
            <p id="dashboardError" class="error"></p>
        </div>
    </section>

<?php elseif ($player): ?>

    <main id="game" class="game">
        <div id="gameContent" class="card center">
            <p class="sub">Loading your quiz...</p>
        </div>
    </main>

<?php else: ?>

    <section class="hero">
        <div class="eyebrow">The ultimate spatial challenge</div>
        <h1>Escape the <span class="gradient">Quiz Room.</span></h1>
        <p class="sub">15 questions. One explorer. Can you unlock every level?</p>
    </section>

    <section class="join-card card">
        <div class="eyebrow">Enter the challenge</div>
        <h2 style="margin-bottom:6px">Join the competition</h2>
        <p class="sub">Enter your name and the code provided by your organizer.</p>

        <form id="joinForm">
            <label for="playerName">Your name</label>
            <input id="playerName" maxlength="80" required placeholder="Enter your display name">

            <label for="joinCode">Competition code</label>
            <input id="joinCode" maxlength="20" required placeholder="Enter competition code">

            <button class="primary full">Enter the quiz room →</button>
            <div id="joinError" class="error"></div>
        </form>
    </section>

    <section class="features">
        <div class="feature"><strong>⏱ Timed challenges</strong><small>25 seconds per question. Keep your focus.</small></div>
        <div class="feature"><strong>🗺 WebGIS knowledge</strong><small>GIS, remote sensing, PostGIS and spatial analysis.</small></div>
        <div class="feature"><strong>🏆 Individual scores</strong><small>Earn 10 points per correct answer and finish all 15 questions.</small></div>
    </section>

<?php endif; ?>

    <footer>ESCAPE THE QUIZ ROOM · WEBGIS KNOWLEDGE CHALLENGE</footer>
</div>

<script>
const API = 'index.php?api=';
const IS_ORGANIZER_PAGE = <?php echo $isOrganizerPage ? 'true' : 'false'; ?>;
const IS_ORGANIZER = <?php echo $isOrganizer ? 'true' : 'false'; ?>;

async function api(action, data = {}) {
    const response = await fetch(API + encodeURIComponent(action), {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data),
        credentials: 'same-origin'
    });

    const result = await response.json();

    if (!response.ok || result.ok === false) {
        throw new Error(result.error || 'Request failed.');
    }

    return result;
}

/* JOIN SCREEN */
const joinForm = document.getElementById('joinForm');

if (joinForm) {
    joinForm.addEventListener('submit', async event => {
        event.preventDefault();
        const error = document.getElementById('joinError');
        error.textContent = '';

        try {
            await api('join', {
                name: document.getElementById('playerName').value.trim(),
                code: document.getElementById('joinCode').value.trim()
            });

            location.reload();
        } catch (e) {
            error.textContent = e.message;
        }
    });
}

/* ORGANIZER LOGIN */
const organizerForm = document.getElementById('organizerForm');

if (organizerForm) {
    organizerForm.addEventListener('submit', async event => {
        event.preventDefault();
        const error = document.getElementById('loginError');
        error.textContent = '';

        try {
            await api('organizer_login', {
                pin: document.getElementById('pin').value
            });

            location.reload();
        } catch (e) {
            error.textContent = e.message;
        }
    });
}

/* ORGANIZER DASHBOARD */
async function loadDashboard() {
    if (!document.getElementById('playersTable')) return;

    const error = document.getElementById('dashboardError');

    try {
        const data = await api('organizer_data');

        document.getElementById('totalPlayers').textContent = data.total;
        document.getElementById('playingPlayers').textContent = data.playing;
        document.getElementById('completedPlayers').textContent = data.completed;

        const body = document.getElementById('playersTable');
        body.replaceChildren();

        if (!data.players.length) {
            const tr = document.createElement('tr');
            const td = document.createElement('td');
            td.colSpan = 7;
            td.textContent = 'No participants have joined yet.';
            tr.appendChild(td);
            body.appendChild(tr);
        }

        data.players.forEach((p, index) => {
            const tr = document.createElement('tr');

            const values = [
                '#' + (index + 1),
                p.player_name,
                p.stage,
                p.score + ' / 150',
                p.correct_count,
                p.timeout_count,
                p.status === 'finished' ? 'Completed' : 'Playing'
            ];

            values.forEach((value, col) => {
                const td = document.createElement('td');

                if (col === 6) {
                    const badge = document.createElement('span');
                    badge.className = 'badge ' +
                        (p.status === 'finished' ? 'done' : 'live');
                    badge.textContent = value;
                    td.appendChild(badge);
                } else {
                    td.textContent = value;
                }

                tr.appendChild(td);
            });

            body.appendChild(tr);
        });

        error.textContent = '';
    } catch (e) {
        error.textContent = e.message;
    }
}

async function logoutOrganizer() {
    try {
        await api('organizer_logout');
        location.reload();
    } catch (e) {
        alert(e.message);
    }
}

async function resetCompetition() {
    if (!confirm('Delete all quiz participants and answers? This cannot be undone.')) {
        return;
    }

    try {
        await api('organizer_reset');
        await loadDashboard();
        alert('Competition data has been reset.');
    } catch (e) {
        alert(e.message);
    }
}

if (IS_ORGANIZER_PAGE && IS_ORGANIZER) {
    loadDashboard();
    setInterval(loadDashboard, 4000);
}

/* PARTICIPANT GAME */
const gameContent = document.getElementById('gameContent');
let timerInterval = null;
let requestBusy = false;
let currentScreen = '';
let lastQuestionNumber = null;

function stopTimer() {
    if (timerInterval) {
        clearInterval(timerInterval);
        timerInterval = null;
    }
}

function escapeHTML(value) {
    return String(value).replace(/[&<>"']/g, char => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    })[char]);
}

function renderQuestion(data) {
    stopTimer();
    currentScreen = 'question';
    lastQuestionNumber = data.question_number;

    gameContent.innerHTML = `
        <div class="game-meta">
            <div class="stat">
                <small>EXPLORER</small>
                <strong>${escapeHTML(data.name)}</strong>
            </div>
            <div class="stat">
                <small>YOUR SCORE</small>
                <strong id="scoreDisplay">${data.score} / 150</strong>
            </div>
            <div class="timer" id="timerDisplay">${data.remaining}</div>
        </div>

        <div class="progress">
            <div style="width:${(data.question_number / data.total) * 100}%"></div>
        </div>

        <div style="text-align:left">
            <div class="topic">${escapeHTML(data.topic)}</div>
            <p class="muted">QUESTION ${data.question_number} OF ${data.total}</p>
            <h2 class="question">${escapeHTML(data.question)}</h2>

            <div class="options" id="options">
                ${data.options.map((option, i) => `
                    <button class="option" data-answer="${i}">
                        <span class="letter">${String.fromCharCode(65 + i)}</span>
                        <span>${escapeHTML(option)}</span>
                    </button>
                `).join('')}
            </div>
            <p id="answerError" class="error"></p>
            <div class="row">
                <button class="primary" id="submitAnswer" disabled>Lock in answer →</button>
            </div>
        </div>
    `;

    let selected = null;
    const buttons = [...gameContent.querySelectorAll('[data-answer]')];
    const submit = document.getElementById('submitAnswer');

    buttons.forEach(button => {
        button.addEventListener('click', () => {
            if (selected !== null || requestBusy) return;

            selected = Number(button.dataset.answer);

            buttons.forEach(b => b.classList.remove('selected'));
            button.classList.add('selected');
            submit.disabled = false;
        });
    });

    submit.addEventListener('click', async () => {
        if (selected === null || requestBusy) return;

        requestBusy = true;
        submit.disabled = true;
        buttons.forEach(b => b.disabled = true);

        try {
            await api('answer', {answer: selected});
            await refreshGame();
        } catch (e) {
            document.getElementById('answerError').textContent = e.message;

            /*
             * Reload server state. This is important if the server deadline
             * passed between displaying the question and submitting.
             */
            await refreshGame();
        } finally {
            requestBusy = false;
        }
    });

    let remaining = data.remaining;
    const timerDisplay = document.getElementById('timerDisplay');

    timerInterval = setInterval(async () => {
        remaining = Math.max(0, remaining - 1);
        timerDisplay.textContent = remaining;

        if (remaining <= 5) timerDisplay.classList.add('urgent');

        if (remaining <= 0) {
            stopTimer();
            await refreshGame();
        }
    }, 1000);
}

function renderFeedback(data) {
    stopTimer();
    currentScreen = 'feedback';

    let title;
    let emoji;

    if (data.timed_out) {
        title = 'Time is up!';
        emoji = '⏳';
    } else if (data.correct) {
        title = 'Excellent! Correct answer.';
        emoji = '🎉';
    } else {
        title = 'Not quite. Keep going!';
        emoji = '🧭';
    }

    const points = data.correct ? '+10 points' : '+0 points';

    gameContent.innerHTML = `
        <div class="feedback">
            <div class="emoji">${emoji}</div>
            <div class="eyebrow">Question ${data.question_number} completed</div>
            <h2>${title}</h2>
            <div class="bigscore">${data.score}</div>
            <p class="muted">TOTAL SCORE · ${points}</p>
        </div>

        <div style="text-align:left">
            <div class="topic">${escapeHTML(data.topic)}</div>
            <h3>Learning note</h3>
            <p class="sub">${escapeHTML(data.explanation)}</p>
        </div>

        <div class="row">
            <button class="primary" id="nextQuestion">
                ${data.question_number === data.total
                    ? 'View final results →'
                    : 'Continue to next question →'}
            </button>
        </div>
    `;

    document.getElementById('nextQuestion').addEventListener('click', async () => {
        if (requestBusy) return;
        requestBusy = true;

        try {
            await api('next');
            await refreshGame();
        } catch (e) {
            alert(e.message);
        } finally {
            requestBusy = false;
        }
    });
}

function renderFinished(data) {
    stopTimer();
    currentScreen = 'finished';

    gameContent.innerHTML = `
        <div class="feedback">
            <div class="emoji">🏆</div>
            <div class="eyebrow">Escape complete</div>
            <h1>You made it, <span class="gradient">${escapeHTML(data.name)}!</span></h1>
            <p class="sub">You completed all ${data.total} WebGIS questions.</p>
            <div class="bigscore">${data.score}<span style="font-size:20px"> / 150</span></div>
            <p class="sub">Final score</p>
        </div>

        <div class="features">
            <div class="feature"><strong>${data.correct_count}</strong><small>Correct answers</small></div>
            <div class="feature"><strong>${data.total - data.correct_count - data.timeout_count}</strong><small>Incorrect answers</small></div>
            <div class="feature"><strong>${data.timeout_count}</strong><small>Timed out</small></div>
        </div>

        <p class="sub center">
            Your organizer can see your final score on the competition dashboard.
        </p>
        <button class="secondary full" onclick="location.href='index.php'">
            Return to home
        </button>
    `;
}

async function refreshGame() {
    if (!gameContent || requestBusy) return;

    try {
        const data = await api('state');

        if (data.screen === 'question') {
            /*
             * Avoid replacing an active question unnecessarily if a
             * future polling mechanism is added.
             */
            renderQuestion(data);
        } else if (data.screen === 'feedback') {
            renderFeedback(data);
        } else if (data.screen === 'finished') {
            renderFinished(data);
        }
    } catch (e) {
        gameContent.innerHTML = `
            <h2>Connection interrupted</h2>
            <p class="sub">${escapeHTML(e.message)}</p>
            <button class="primary" onclick="refreshGame()">Reconnect</button>
        `;
    }
}

if (gameContent) {
    refreshGame();
}
</script>
</body>
</html>
