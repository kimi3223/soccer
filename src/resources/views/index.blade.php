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

<div>
    <label for="match-date">試合日時：</label>
    <input type="datetime-local" id="match-date" name="match-date"><br>
    <div>
        <label for="team-goals">バサラ</label>
        <span id="team1-goals">0</span>
        <label for="team-goals"> - </label>
        <span id="team2-goals">0</span>
        <span id="team2-name"></span>
    </div>
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

<div>
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
</div>

<!-- 選手情報入力フォーム -->
<div class="player-form-container" id="player-form-container" >
    <div id="player-form" class="player-form">
        <label for="player-number">背番号：</label>
        <input type="number" id="player-number" name="player-number" min="1" max="99"><br>
        <label for="player-foot">利き足：</label>
        <select id="player-foot" name="player-foot">
            <option value="右足">右足</option>
            <option value="左足">左足</option>
            <option value="両足">両足</option>
        </select><br>
        <label for="player-feature">特徴：</label>
        <select id="player-feature">
            <option value="dribble">ドリブル</option>
            <option value="long-pass">ロングパス</option>
            <option value="physical">フィジカル</option>
            <option value="both-feet">両脚</option>
        </select><br>
        <label for="player-goals">ゴール数：</label>
        <input type="number" id="player-goals" name="player-goals" min="0" max="20"><br>
        <button id="update-player">選手情報更新</button>
        <button id="close-form">閉じる</button>
    </div>
</div>

<!-- チーム2の選択ボックス -->
<div>
    <label for="teamSelectRight">相手チーム:</label>
    <select id="teamSelectRight"></select>
    <button onclick="saveOpponentTeam()">相手チームを保存する</button>
</div>

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

<!-- ホームの右上のヘッダー部分に一括保存ボタン -->
<button id="save-all-players">一括保存</button>

<script>
    // generatePlayers関数を定義
function generatePlayers(container, formation, isLeftSide) {
    const positions = formation.split('-');
    const containerHeight = container.offsetHeight;
    const playerFormContainer = document.getElementById('player-form-container');
    let topPosition = (containerHeight - 40 * 7) / 2; // フィールドの縦半分に配置

    // フォーメーションに基づいて選手を配置
    for (let i = 0; i < positions.length; i++) {
        const playersInLine = parseInt(positions[i]);
        const spaceBetweenPlayers = (containerHeight - 40 * playersInLine) / (playersInLine + 1);

        for (let j = 0; j < playersInLine; j++) {
            const player = document.createElement('div');
            player.className = 'player';
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
                // フォームを選手の右側に表示
                playerFormContainer.style.left = `${player.offsetLeft + player.offsetWidth}px`;
                playerFormContainer.style.top = `${player.offsetTop}px`;
                playerFormContainer.classList.add('active');
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const team1Container = document.getElementById('team1-container');
    const team2Container = document.getElementById('team2-container');
    const playerFormContainer = document.getElementById('player-form-container');
    const playerForm = document.getElementById('player-form');
    const teamSelectRight = document.getElementById('teamSelectRight');

    // チーム名を選択ボックスに追加する関数
    function addTeamOption(teamId, teamName) {
        const option = document.createElement('option');
        option.value = teamId;
        option.textContent = teamName;
        teamSelectRight.appendChild(option);
    }


    // 選手をクリックした場合のイベントリスナー（チーム1）
    team1Container.addEventListener('click', (event) => {
        const player = event.target;
        if (player.classList.contains('player')) {
            if (playerFormContainer) {
                // フォームを選手の右側に表示
                playerFormContainer.style.left = `${player.offsetLeft + player.offsetWidth}px`;
                playerFormContainer.style.top = `${player.offsetTop}px`;
                playerFormContainer.classList.add('active');
            }
        }
    });

    // 選手をクリックした場合のイベントリスナー（チーム2）
    team2Container.addEventListener('click', (event) => {
        const player = event.target;
        if (player.classList.contains('player')) {
            if (playerFormContainer) {
                // フォームを選手の右側に表示
                playerFormContainer.style.left = `${player.offsetLeft + player.offsetWidth}px`;
                playerFormContainer.style.top = `${player.offsetTop}px`;
                playerFormContainer.classList.add('active');
            }
        }
    });

    // チームIDを保持する変数
    var selectedTeamId = null;

    // チームを選択した際にチームIDを保持する関数
    function selectTeam(teamId) {
        selectedTeamId = teamId;
    }

    // 相手チームを保存する関数
    function saveOpponentTeam() {
        // 選択された相手チームのIDを取得
        const opponentTeamId = document.getElementById('teamSelectRight').value;

        // サーバーにデータを送信して保存する処理
        fetch('/save-match-opponent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                opponentTeamId: opponentTeamId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Match opponent saved:', data);
            // 保存が成功した場合の処理を記述
        })
        .catch(error => {
            console.error('Error saving match opponent:', error);
            // エラーが発生した場合の処理を記述
        });
    }

    // 選手情報更新ボタンのクリックイベント
    document.getElementById('update-player').addEventListener('click', () => {
        playerFormContainer.classList.remove('active'); // フォームを非表示にする
    });

    // 入力フォームを閉じるボタンのクリックイベント
    document.getElementById('close-form').addEventListener('click', () => {
        playerFormContainer.classList.remove('active'); // フォームを非表示にする
    });

    // フォーメーション選択ボックスの変更時に選手配置を更新する
    function handleFormationSelect() {
        team1Container.innerHTML = ''; // 選手をクリア
        team2Container.innerHTML = ''; // 選手をクリア

        const team1Formation = document.getElementById('team1-formation').value;
        const team2Formation = document.getElementById('team2-formation').value;

        generatePlayers(team1Container, team1Formation, true); // チーム1は左側
        generatePlayers(team2Container, team2Formation, false); // チーム2は右側
    }

    // フォーメーション選択ボックスの変更時に選手配置を更新する
    document.getElementById('team1-formation').addEventListener('change', handleFormationSelect);
    document.getElementById('team2-formation').addEventListener('change', handleFormationSelect);

    // 初期配置を生成
    handleFormationSelect();
});

// チーム名を選択ボックスから取得して表示する関数
function displaySelectedTeamName() {
    const teamSelectRight = document.getElementById('teamSelectRight');
    const team2NameElement = document.getElementById('team2-name');
    if (teamSelectRight && team2NameElement) {
        const selectedTeamName = teamSelectRight.options[teamSelectRight.selectedIndex].text;
        team2NameElement.textContent = selectedTeamName;
    }
}

// 選択ボックスの変更時にチーム名を表示更新する
document.getElementById('teamSelectRight').addEventListener('change', displaySelectedTeamName);

// 初期表示も行う
displaySelectedTeamName();

</script>
<footer>
    <p>&copy; 2024 Soccer App</p>
</footer>
</body>
</html>
