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
        <!-- ナビゲーションバー -->
        <nav>
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/matches">Matches</a></li>
                <li><a href="/teams/create">Teams</a></li>
            </ul>
        </nav>
  </header>

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

    <!-- チーム2の選択ボックス -->
    <div>
        <label for="teamSelectRight">相手チーム:</label>
        <select id="teamSelectRight"></select>
        <button onclick="saveOpponentTeam()">相手チームを保存する</button>
    </div>

    <!-- チーム2の選択ボックス -->
    @isset($teams)
    <script>
        // BladeテンプレートからPHP変数をJavaScriptに渡す
        const teamsData = @json($teams);
        console.log(teamsData); // データが正しく渡されていることを確認
    </script>
    @endisset

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

    <!-- ホームの右上のヘッダー部分に一括保存ボタン -->
    <button id="save-all-players">一括保存</button>

    <script>

      // チームIDを保持する変数
var selectedTeamId = null;

// チームを選択した際にチームIDを保持する関数
function selectTeam(teamId) {
    selectedTeamId = teamId;
}

    document.addEventListener('DOMContentLoaded', function() {
        const team1Container = document.getElementById('team1-container');
        const team2Container = document.getElementById('team2-container');
        const playerFormContainer = document.getElementById('player-form-container');
        const playerForm = document.getElementById('player-form');
        const teamSelectRight = document.getElementById('teamSelectRight');

    function generatePlayers(container, formation, isLeftSide) {
        const positions = formation.split('-');
        const containerHeight = container.offsetHeight;
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

    // フォーメーション選択時の処理
    function handleFormationSelect() {
        team1Container.innerHTML = ''; // 選手をクリア
        team2Container.innerHTML = ''; // 選手をクリア

        const team1Formation = document.getElementById('team1-formation').value;
        const team2Formation = document.getElementById('team2-formation').value;

        generatePlayers(team1Container, team1Formation, true); // チーム1は左側
        generatePlayers(team2Container, team2Formation, false); // チーム2は右側
    }

    // 選手情報更新ボタンのクリックイベント
    document.getElementById('update-player').addEventListener('click', () => {
        playerFormContainer.classList.remove('active'); // フォームを非表示にする
    });

    // 入力フォームを閉じるボタンのクリックイベント
    document.getElementById('close-form').addEventListener('click', () => {
        playerFormContainer.classList.remove('active'); // フォームを非表示にする
    });

    // 一括保存ボタンのクリックイベント
    document.getElementById('save-all-players').addEventListener('click', () => {
        // データベースにデータを保存する処理
    });

    // フォーメーション選択ボックスの変更時に処理を実行
    document.getElementById('team1-formation').addEventListener('change', handleFormationSelect);
    document.getElementById('team2-formation').addEventListener('change', handleFormationSelect);

    // ページロード時に初期配置を生成
    handleFormationSelect();
});

    // fetchTeams関数を削除し、Bladeテンプレートから渡されたデータを使用する
    document.addEventListener('DOMContentLoaded', function() {
    // teamsDataを使用して選択ボックスにチーム名を追加する
    teamsData.forEach(team => {
        const option = document.createElement('option');
        option.value = team.id; // チームのIDをvalueとしてセット
        option.textContent = team.name; // チーム名を表示
        document.getElementById('teamSelectRight').appendChild(option);
    });
});
    </script>
<footer>
    <p>&copy; 2024 Soccer App</p>
</footer>
</body>
</html>
