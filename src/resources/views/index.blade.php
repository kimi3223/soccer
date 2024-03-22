<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Soccer</title>
<style>
    body, html {
        margin: 0;
        padding: 0;
        height: 100%;
    }

    /* リンクの下線を無くす */
    nav ul li a {
        text-decoration: none;
        outline: none;
        font-size: 22px;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        /* 文字色と影の設定 */
        color: rgb(0, 238, 255);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* 可愛いフォントファミリー */
    }

    /* ボタンの黒点（フォーカスリング）を削除 */
    nav ul li a:focus {
        outline: none;
    }

    .field {
        width: 100%;
        height: 100%;
        background-image: url('{{ asset('images/soccer_field.jpg') }}');
        background-size: cover;
        background-position: center;
        position: relative;
    }

    .field {
        display: flex;
    }

    .team-container {
        width: 50%;
        position: relative;
        border: 1px solid #000;
    }

    .player {
        position: absolute;
        width: 40px;
        height: 40px;
        background-color: blue;
        color: white;
        border-radius: 50%;
        text-align: center;
        line-height: 40px;
        font-size: 14px;
        cursor: pointer;
    }

    .player-container {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #ccc;
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 20px;
    }

    .player-number {
        font-size: 24px;
        font-weight: bold;
    }

    .player-form-container {
    position: absolute;
    display: none;
    background-color: #ffffff;
    border: 1px solid #000000;
    padding: 10px;
    z-index: 999; /* 選手の上に表示されるようにする */
    }

    .box {
        border: 4px solid rgb(0, 255, 234);
        border-radius: 10px;
        padding: 10px;
        margin: 10px;
        font-size: 22px;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        /* 文字色と影の設定 */
        color: rgb(0, 238, 255);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* 可愛いフォントファミリー */
    }

    .player-form-container.active {
    display: flex;
    top: calc(50% - 100px); /* 選手の中央に配置 */
    left: calc(50% + 50px); /* 選手の右側に配置 */
    /* 他の適切なスタイルを追加 */
    }

    .player-form {
    background-color: #fff;
    border: 1px solid #000;
    padding: 10px;
    }

    #save-all-players {
    float: right;
    }

    /* モーダルのスタイル */
    .modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    background-color: rgba(0, 0, 0, 0.5);
    padding: 20px;
    border-radius: 10px;
    }

    .modal-content {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    }

    .close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    }

    .close:hover,
    .close:focus {
    color: black;
    text-decoration: none;
    cursor: pointer;
    }

    /* チーム1の選手の背景色を青に設定 */
    .player.team1 {
        background-color: blue;
        color: white; /* 選手の文字色を白に設定 */
    }

    /* チーム2の選手の背景色を赤に設定 */
    .player.team2 {
    background-color: red;
    color: white; /* 選手の文字色を白に設定 */
    }

    footer {
        text-align: center; /* テキストを中央揃えにする */
    }
</style>
</head>

<body>
<header>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/matches">Matches</a></li>
            <li><a href="/teams/create">Teams</a></li>
        </ul>
    </nav>
</header>

<div class="box">
<select id="teamSelectLeft">
    @foreach($teams as $team)
        @if($team->name === 'バサラ')
            <option value="{{ $team->id }}" selected>{{ $team->name }}</option>
        @else
            <option value="{{ $team->id }}">{{ $team->name }}</option>
        @endif
    @endforeach
</select>
<!-- チーム2の選択ボックス -->
    <label for="teamSelectRight">VS</label>
    <select id="teamSelectRight"></select>
    <div>
        <label for="team-goals">バサラ</label>
        <span id="team1-goals">0</span>
        <label for="team-goals"> - </label>
        <span id="team2-goals">0</span>
        <span id="team2-name"></span>
    </div>
    <!-- チーム1のフォーメーション選択ボックス -->
    <select id="team1-formation">
        <option value="3-3-1">3-3-1</option>
        <option value="2-4-1">2-4-1</option>
        <option value="3-2-2">3-2-2</option>
        <option value="2-3-2">2-3-2</option>
    </select>

    <!-- チーム2のフォーメーション選択ボックス -->
    <select id="team2-formation">
        <option value="3-3-1">3-3-1</option>
        <option value="2-4-1">2-4-1</option>
        <option value="3-2-2">3-2-2</option>
        <option value="2-3-2">2-3-2</option>
    </select>
    <button id="save-match-data">保存</button>
</div>

<div class="field">
    <!-- チーム1の選手配置フォーム（左側） -->
    <div class="team-container" id="team1-container">
        <!-- 選手の配置はJavaScriptで動的に生成 -->
    </div>

    <!-- チーム2の選手配置フォーム（右側） -->
    <div class="team-container" id="team2-container">
        <!-- 選手の配置はJavaScriptで動的に生成 -->
    </div>
</div>

<!-- 選手情報入力フォーム -->
<form action="/save-player-data" method="POST">
    @csrf
    <div class="player-form-container" id="player-form-container">
        <div id="player-form" class="player-form">
            <select id="teamSelect" name="team_id">
                @foreach($teams as $team)
                    <option value="{{ $team->id }}">{{ $team->name }}</option>
                @endforeach
            </select><br>
            <label for="player-number">背番号：</label>
            <input type="number" id="player-number" name="player_number" min="1" max="99" required><br>
            <!-- 利き足のセレクトボックスのname属性が抜けていたので追加 -->
            <label for="player-foot">利き足：</label>
            <select id="player-foot" name="player_foot">
                <option value="右足">右足</option>
                <option value="左足">左足</option>
                <option value="両足">両足</option>
            </select><br>
            <!-- 特徴のセレクトボックスのname属性が抜けていたので追加 -->
            <label for="player-feature">特徴：</label>
            <select id="player-feature" name="player_feature">
                <option value="dribble">ドリブル</option>
                <option value="long-pass">ロングパス</option>
                <option value="physical">フィジカル</option>
                <option value="both-feet">両脚</option>
            </select><br>
            <!-- ゴール数の入力欄のname属性が抜けていたので追加 -->
            <label for="player-goals">ゴール数：</label>
            <input type="number" id="player-goals" name="player_goals" min="0" max="20" required><br>
            <!-- 閉じるボタンのid属性が抜けていたので追加 -->
            <button id="save-player-data">選手データを保存</button>
            <button id="close-form" type="button">閉じる</button>
        </div>
    </div>
</form>

@isset($teams)
<script>
    const teamsData = @json($teams); // PHP変数からJavaScript配列に変換

    const teamSelectRight = document.getElementById('teamSelectRight');

    // 選択ボックスにチーム名を追加する
    teamsData.forEach(team => {
        const option = document.createElement('option');
        option.value = team.id; // チームIDを値として設定
        option.textContent = team.name; // チーム名を表示
        teamSelectRight.appendChild(option);
    });
</script>
@endisset

<script>
document.addEventListener('DOMContentLoaded', function() {
    const team1Container = document.getElementById('team1-container');
    const team2Container = document.getElementById('team2-container');
    const playerFormContainer = document.getElementById('player-form-container');
    const savePlayerDataBtn = document.getElementById('save-player-data');
    const playerForm = document.getElementById('player-form');
    const teamSelect = document.getElementById('teamSelect');
    const saveMatchDataBtn = document.getElementById('save-match-data');

    if (saveMatchDataBtn) {
        saveMatchDataBtn.addEventListener('click', () => {
            const opponentTeamId = document.getElementById('teamSelectRight').value;
            const matchDate = new Date().toISOString(); // 現在の日時を取得（仮定）
            const team1Formation = document.getElementById('team1-formation').value;
            const team2Formation = document.getElementById('team2-formation').value;

            saveMatchData(opponentTeamId, matchDate, team1Formation, team2Formation);
        });
    } else {
        console.error('save-match-data ボタンが見つかりません。');
    }

    // generatePlayers関数を定義
    function generatePlayers(container, formation, isLeftSide, teamClass) {
        const positions = formation.split('-');
        const containerHeight = container.offsetHeight;
        let topPosition = (containerHeight - 40 * 7) / 2; // フィールドの縦半分に配置

        // フォーメーションに基づいて選手を配置
        for (let i = 0; i < positions.length; i++) {
            const playersInLine = parseInt(positions[i]);
            const spaceBetweenPlayers = (containerHeight - 40 * playersInLine) / (playersInLine + 1);

            for (let j = 0; j < playersInLine; j++) {
                const player = document.createElement('div');
                player.className = `player ${teamClass}`; // チームを表すクラスを追加
                player.style.top = `${topPosition + spaceBetweenPlayers * (j + 1)}px`;

                if (isLeftSide) {
                    player.style.left = `${20 + i * 30}%`;
                } else {
                    player.style.right = `${20 + i * 30}%`;
                }

                player.textContent = `#${container.children.length + 1}`;
                container.appendChild(player);

                // 選手をクリックした場合のイベントリスナー
                player.addEventListener('click', () => {
                    const playerFormContainer = document.getElementById('player-form-container');
                    if (playerFormContainer) {
                        // フォームを選手の右側に表示
                        playerFormContainer.style.left = `${player.offsetLeft + player.offsetWidth}px`;
                        playerFormContainer.style.top = `${player.offsetTop}px`;
                        playerFormContainer.classList.add('active');
                        // フォームに選手情報を表示する処理を追加することもできます
                        // 選手のIDやその他情報をフォームに表示するロジックを記述します
                    }
                    // フォームが送信されたときのイベントリスナーを追加
                    playerForm.addEventListener('submit', function(event) {
                        event.preventDefault(); // フォームのデフォルト動作を停止

                        const selectedTeamId = teamSelect.value;
                        console.log('Selected Team ID:', selectedTeamId);
                        // ここで選手データをサーバーに送信する処理を記述する
                       // fetchやAjaxを使用してデータを送信することができます
                    });
                });
            }
        }
    }

    let selectedTeamId = null;

    function selectTeam(teamId) {
        selectedTeamId = teamId;
    }

    function addTeamOption(teamId, teamName) {
        const option = document.createElement('option');
        option.value = teamId;
        option.textContent = teamName;
        teamSelectRight.appendChild(option);
    }

    document.getElementById('close-form').addEventListener('click', () => {
        playerFormContainer.classList.remove('active');
    });

    function handleFormationSelect() {
        team1Container.innerHTML = '';
        team2Container.innerHTML = '';

        const team1Formation = document.getElementById('team1-formation').value;
        const team2Formation = document.getElementById('team2-formation').value;

        generatePlayers(team1Container, team1Formation, true, 'team1');
        generatePlayers(team2Container, team2Formation, false, 'team2');
    }

    document.getElementById('team1-formation').addEventListener('change', handleFormationSelect);
    document.getElementById('team2-formation').addEventListener('change', handleFormationSelect);

    handleFormationSelect();

    document.getElementById('save-match-data').addEventListener('click', () => {
        const opponentTeamId = document.getElementById('teamSelectRight').value;
        const matchDate = document.getElementById('match-date').value;
        const team1Formation = document.getElementById('team1-formation').value;
        const team2Formation = document.getElementById('team2-formation').value;

        // マッチデータを保存する関数を呼び出す
        saveMatchData(opponentTeamId, matchDate, team1Formation, team2Formation);
    });

    function saveMatchData(opponentTeamId, matchDate, team1Formation, team2Formation) {
        // マッチデータをサーバーに送信して保存
        fetch('/save-match-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                opponent_team: opponentTeamId,
                date: matchDate,
                team1_formation: team1Formation,
                team2_formation: team2Formation
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Match data saved:', data);
            // マッチデータが保存された後の処理を記述（任意）
        })
        .catch(error => {
            console.error('Error saving match data:', error);
        });
    }

    // savePlayerData関数を定義
    function savePlayerData(teamId, playerNumber, playerFoot, playerFeature, playerGoals) {
        // プレイヤーデータをサーバーに送信して保存
        fetch('/save-player-data', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                team_id: teamId,
                player_number: playerNumber,
                foot: playerFoot,
                feature: playerFeature,
                goals: playerGoals
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Player data saved:', data);
            // プレイヤーデータが保存された後の処理を記述（任意）
        })
        .catch(error => {
            console.error('Error saving player data:', error);
        });
    }
});
</script>
<footer>
    <p>&copy; 2024 Soccer App</p>
</footer>
</body>
</html>
