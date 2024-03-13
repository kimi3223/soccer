<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Past match results</title>
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
        <!-- 日付検索フォーム -->
        <div>
            <label for="match-date">試合日付：</label>
            <input type="date" id="match-date" name="match-date">
            <button id="search-match">検索</button>
        </div>
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

  <!-- チーム2の選択ボックス -->
    <div>
        <label for="teamSelectRight">相手チーム:</label>
        <select id="teamSelectRight"></select>
        <button onclick="saveOpponentTeam()">相手チームを保存する</button>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const team1Container = document.getElementById('team1-container');
        const team2Container = document.getElementById('team2-container');
        const playerFormContainer = document.getElementById('player-form-container');
        const playerForm = document.getElementById('player-form');
        const teamSelectRight = document.getElementById('teamSelectRight');

    // 過去の試合データを取得して表示する関数
    function fetchPastMatches() {
        const matchDate = document.getElementById('match-date').value; // 日付検索フォームから日付を取得

        // 過去の試合データを取得するAPIエンドポイントにリクエストを送信
        fetch(`/api/matches?date=${matchDate}`)
            .then(response => response.json()) // レスポンスをJSON形式に変換
            .then(data => {
                // 取得したデータを元に過去の試合データを表示
                displayPastMatches(data);
            })
            .catch(error => {
                console.error('Error fetching past matches:', error);
            });

        // チーム名のリストを取得して選択ボックスに追加
        fetch('/api/teams')
            .then(response => response.json())
            .then(data => {
                data.forEach(team => {
                    const option = document.createElement('option');
                    option.value = team.id;
                    option.textContent = team.name;
                    teamSelectRight.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error fetching teams:', error);
            });
    }

    // 過去の試合データを表示する関数
    function displayPastMatches() {
        // データベースから取得した過去の試合データを元に表示を行う処理
        // チーム1とチーム2の選手を配置する処理は以下の関数を利用する
        generatePlayers(team1Container, '3-3-1', true); // 例：3-3-1のフォーメーションを左側に配置
        generatePlayers(team2Container, '2-4-1', false); // 例：2-4-1のフォーメーションを右側に配置
    }

    // 選手を配置する関数
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
    fetchPastMatches();
    });

    function saveOpponentTeam() {
    const selectedTeamId = document.getElementById('teamSelectRight').value;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content'); // CSRFトークンを取得
    fetch('/api/save-opponent-team', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken // CSRFトークンを追加
        },
        body: JSON.stringify({ teamId: selectedTeamId }),
    })
    .then(response => response.json())
    .then(data => {
        alert('チーム名が保存されました！');
    })
    .catch(error => {
        console.error('Error saving opponent team:', error);
    });
    }
    </script>

    <footer>
      <p>&copy; 2024 Soccer App</p>
    </footer>

</body>
</html>