<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>3-3-1 Formation 3-3-1</title>
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

  .player {
    width: 50px;
    height: 50px;
    background-color: #fff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 50px;
    cursor: pointer;
    font-size: 24px;
  }

  .player span {
  display: inline-block;
  }

  /* チーム1の選手を配置するスタイル */
  #team1 {
    display: flex;
    align-items: center;
    position: absolute;
    left: 25%; /* 左側の中央に配置 */
    top: 50%; /* 上側の中央に配置 */
    transform: translate(-50%, -50%); /* 中央揃え */
  }

  #team1 .row {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 20px;
    margin-top: 40px
  }

  /* チーム1の選手を配置するスタイル */
  #team2 {
    display: flex;
    align-items: center;
    position: absolute;
    left: 75%; /* 左側の中央に配置 */
    top: 50%; /* 上側の中央に配置 */
    transform: translate(-50%, -50%); /* 中央揃え */
  }

  #team2 .row {
    display: flex;
    flex-direction: column;
    align-items: center;
    margin-right: 20px;
    margin-top: 40px
  }

  .player7 {
    width: 50px;
    height: 50px;
    background-color: #fff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    margin-right: 20px;
  }

  /* 選択ボックスのスタイル */
  #teamSelectLeft {
    position: absolute;
    top: 50px; /* 上側からの距離 */
    left: 10px; /* 左側からの距離 */
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
  }

  #teamSelectRight {
    position: absolute;
    right: 1px; /* 右側からの距離 */
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
  }

  /* 選手の背番号のスタイル */
  .player-number {
    width: 30px;
    height: 30px;
    background-color: #4CAF50; /* 青色 */
    color: #fff;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
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

  /* 新しいフォーメーションのスタイル */
  #team1 .player:nth-child(4),
  #team1 .player:nth-child(5) {
    margin-right: 0; /* 3-2-2の場合、この選手の間隔は不要 */
  }

  #team1 .player:nth-child(5),
  #team1 .player:nth-child(6) {
    margin-bottom: 0; /* 3-2-2の場合、この選手の下の間隔は不要 */
  }

  #team1 .player7 {
    margin-right: 20px; /* フォーメーションに基づいて適切な間隔を設定 */
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
  <!-- チーム1の選手を配置する -->
  <div id="team1" class="team-container">
    <div class="row">
      <div class="player" onclick="showPlayerInfo('Player 1')"></div>
      <div class="player" onclick="showPlayerInfo('Player 2')"></div>
      <div class="player" onclick="showPlayerInfo('Player 3')"></div>
    </div>
    <div class="row">
      <div class="player" onclick="showPlayerInfo('Player 4')"></div>
      <div class="player" onclick="showPlayerInfo('Player 5')"></div>
      <div class="player" onclick="showPlayerInfo('Player 6')"></div>
    </div>
    <div class="row">
      <div class="player" onclick="showPlayerInfo('Player 7')"></div>
    </div>
  </div>

  <!-- チーム2の選手を配置する（未実装） -->
  <div id="team2" class="team-container">
    <div class="player7" onclick="showPlayerInfo('Player 2-7')"></div>
    <div class="row">
      <div class="player" onclick="showPlayerInfo('Player 2-6')"></div>
      <div class="player" onclick="showPlayerInfo('Player 2-5')"></div>
      <div class="player" onclick="showPlayerInfo('Player 2-4')"></div>
    </div>
    <div class="row">
      <div class="player" onclick="showPlayerInfo('Player 2-3')"></div>
      <div class="player" onclick="showPlayerInfo('Player 2-2')"></div>
      <div class="player" onclick="showPlayerInfo('Player 2-1')"></div>
    </div>
  </div>
</div>

<!-- チーム1の選択ボックス -->
<div id="teamNameLeft" style="position: absolute; top: 30%; left: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 5px; background-color: #fff;">
  バサラ兵庫
</div>
<select id="teamSelectLeft" style="position: absolute; top: 40%; left: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 5px; background-color: #fff;" onchange="updateTeamFormationLeft('team1')">
  <option value="3-3-1">3-3-1</option>
  <option value="3-3-1">2-4-1</option>
  <option value="3-3-1">3-2-2</option>
  <option value="3-3-1">2-3-2</option>
  <!-- 他のフォーメーションも選択肢として追加 -->
</select>

<!-- チーム2の選択ボックス -->
@isset($teams)
    <div id="teamNameRight" style="position: absolute; top: 30%; right: 10px; padding: 5px;">
        <select id="teamSelectRight" style="border: none;" onchange="updateTeamId()">
            @foreach($teams as $team)
                <option value="{{ $team->id }}">{{ $team->name }}</option>
            @endforeach
        </select>
    </div>
@endisset

<select id="teamSelectRight" style="position: absolute; top: 40%; right: 10px; padding: 5px; border: 1px solid #ccc; border-radius: 5px; background-color: #fff;" onchange="updateTeamFormationRight('team2')">
  <option value="3-3-1">3-3-1</option>
  <option value="3-3-1">3-2-2</option>
  <option value="3-3-1">2-4-1</option>
  <option value="3-3-1">2-3-2</option>
  <!-- 他のフォーメーションも選択肢として追加 -->
</select>

<button onclick="saveAllPlayers()" style="position: absolute; top: 10%; left: 90%;">一括保存</button>

<!-- モーダル -->
<div id="playerModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>選手情報</h2>
    <!-- 選手情報フォームと表示 -->
    <div id="playerDetails" style="background-color: #fff; border: 1px solid #ccc; padding: 10px; border-radius: 5px;">
      <!-- 選手情報フォーム -->
      <div id="playerForm" style="position: relative;">
        <label for="playerNumber">背番号:</label>
        <input type="number" id="playerNumber" name="playerNumber" min="1" max="99">
        <br>
        <label for="foot">利き足:</label>
        <select id="foot">
          <option value="right">右足</option>
          <option value="left">左足</option>
        </select>
        <br>
        <label for="goals">ゴール数:</label>
        <input type="number" id="goals" name="goals" min="0" max="20">
        <br>
        <label for="feature">特徴:</label>
        <select id="feature">
          <option value="dribble">ドリブル</option>
          <option value="long-pass">ロングパス</option>
          <option value="physical">フィジカル</option>
          <option value="both-feet">両脚</option>
        </select>
        <br>
        <button onclick="savePlayer()">保存</button>
      </div>
      <!-- 選手情報フォームここまで -->

      <!-- 選手情報表示 -->
      <div id="playerInfo" style="margin-top: 10px;">
        <p>選手情報</p>
        <p>背番号: <span id="displayPlayerNumber"></span></p>
        <p>利き足: <span id="displayFoot"></span></p>
        <p>ゴール数: <span id="displayGoals"></span></p>
        <p>特徴: <span id="displayFeature"></span></p>
      </div>
      <!-- 選手情報表示ここまで -->
    </div>
    <!-- 選手情報フォームと表示ここまで -->
  </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
// チームIDを保持する変数
var selectedTeamId = null;

// チームを選択した際にチームIDを保持する関数
function selectTeam(teamId) {
    selectedTeamId = teamId;
}

// 選手情報をサーバーサイドに送信して保存する関数
function savePlayer() {
    var teamId = $("#teamSelectRight").val(); // 選択されたチームのIDを取得
    var playerNumber = $("#playerNumber").val();
    var foot = $("#foot").val();
    var goals = $("#goals").val();
    var feature = $("#feature").val();

    var playerInfo = {
        team_id: teamId, // 選択されたチームのIDを選手情報に含める
        playerNumber: playerNumber,
        foot: foot,
        goals: goals,
        feature: feature
    };

    // CSRF トークンを取得
    var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Ajaxリクエストを送信して選手情報を保存
    $.ajax({
        url: '/savePlayer',
        type: 'POST',
        data: playerInfo,
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
            console.log(response);
            closeModal();
            location.reload();
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            alert('エラーが発生しました。選手情報の保存に失敗しました。');
        }
    });
}

// チームIDを更新する関数
function updateTeamId() {
    var teamId = $("#teamSelectRight").val(); // 選択されたチームのIDを取得
    selectTeam(teamId); // 選択されたチームIDを保持する

    // モーダルを開く
    showPlayerInfoModal();
}

// 選手情報入力用のモーダルを表示する関数
function showPlayerInfoModal() {
    var modal = document.getElementById("playerModal");
    var playerInfo = document.getElementById("playerInfo");
    var playerForm = document.getElementById("playerForm");

    if (playerInfo) {
        playerInfo.innerHTML = ""; // 選手情報をクリア
    }

    if (playerForm) {
        playerForm.style.display = "block"; // 選手情報フォームを表示する
    }

    modal.style.display = "block"; // モーダルを表示する
}

// ページ読み込み時にモーダルを非表示にする
$(document).ready(function() {
    closeModal();
});

// 選手をクリックした際にモーダルを表示する関数
function showPlayerInfo(player) {
    var modal = document.getElementById("playerModal");
    var playerInfo = document.getElementById("playerInfo");
    var playerForm = document.getElementById("playerForm");

    if (playerInfo) {
        playerInfo.innerHTML = "選手名: " + player; // 選手の情報を表示する
    }

    if (playerForm) {
        playerForm.style.display = "block"; // 選手情報フォームを表示する
    }

    modal.style.display = "block"; // モーダルを表示する
}

// フォームの背番号入力欄の内容をリセット
$("#playerNumber").val("");

// モーダルを閉じる関数
function closeModal() {
    var modal = document.getElementById("playerModal");
    if (modal) {
        modal.style.display = "none";
    }
}

// 一括保存ボタンがクリックされたときの処理
function saveAllPlayers() {
    var teamId = $("#teamSelectRight").val(); // チームIDを取得
    var matchFormation = $("#teamSelectRight").val(); // マッチのフォーメーションを取得

    // 7人分の選手情報を収集
    var playersData = [];
    for (var i = 1; i <= 7; i++) {
        var playerNumber = $("#playerNumber" + i).val();
        var foot = $("#foot" + i).val();
        var goals = $("#goals" + i).val();
        var feature = $("#feature" + i).val();

        // 選手情報をオブジェクトに追加
        var playerInfo = {
            team_id: teamId,
            playerNumber: playerNumber,
            foot: foot,
            goals: goals,
            feature: feature
        };

        playersData.push(playerInfo);
    }

    // Ajaxリクエストを送信して選手情報を保存
    $.ajax({
        url: '/savePlayersAndMatch', // サーバーの一括保存用エンドポイントを指定
        type: 'POST', // POSTリクエストを送信
        data: {
            matchFormation: matchFormation, // マッチのフォーメーションを送信
            players: playersData // 一括保存する選手情報をリクエストのデータとして送信
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRFトークンをリクエストヘッダーに追加
        },
        success: function(response) {
            console.log(response);
            closeModal(); // モーダルを閉じる
            location.reload(); // ページをリロードして更新
        },
        error: function(xhr) {
            console.log(xhr.responseText);
            alert('エラーが発生しました。選手情報の保存に失敗しました。');
        }
    });
}

function updateTeamFormationLeft(team) {
    var selectedFormation = document.getElementById('teamSelectLeft').value;

    // Ajaxリクエストを送信してフォーメーション情報をサーバーに送信
    $.ajax({
        type: 'POST',
        url: '/saveTeamFormation',
        data: {
            team: team,
            formation: selectedFormation
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Left formation saved successfully!');
        },
        error: function(xhr, status, error) {
            console.error('Error occurred while saving left formation:', error);
        }
    });
}

function updateTeamFormationRight(team) {
    var selectedFormation = document.getElementById('teamSelectRight').value;

    // Ajaxリクエストを送信してフォーメーション情報をサーバーに送信
    $.ajax({
        type: 'POST',
        url: '/saveTeamFormation',
        data: {
            team: team,
            formation: selectedFormation
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log('Right formation saved successfully!');
        },
        error: function(xhr, status, error) {
            console.error('Error occurred while saving right formation:', error);
        }
    });
}

</script>

<footer>
    <p>&copy; 2024 Soccer App</p>
</footer>
</body>
</html>