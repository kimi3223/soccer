<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>サッカーグラウンド</title>
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
  /* Add additional styles here if needed */

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
    top: 10px; /* 上側からの距離 */
    left: 10px; /* 左側からの距離 */
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
  }

  #teamSelectRight {
    position: absolute;
    top: 10px; /* 上側からの距離 */
    right: 10px; /* 右側からの距離 */
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #fff;
  }

</style>
</head>
<body>
<div class="field">
  <!-- チーム1の選手を配置する -->
  <div id="team1" class="team-container">
    <div class="row">
      <div class="player" onclick="showPlayerInfo('Player 1')">1</div>
      <div class="player" onclick="showPlayerInfo('Player 2')">2</div>
      <div class="player" onclick="showPlayerInfo('Player 3')">3</div>
    </div>
    <div class="row">
      <div class="player" onclick="showPlayerInfo('Player 4')">4</div>
      <div class="player" onclick="showPlayerInfo('Player 5')">5</div>
      <div class="player" onclick="showPlayerInfo('Player 6')">6</div>
    </div>
    <div class="player7" onclick="showPlayerInfo('Player 7')">7</div>
  </div>

  <!-- チーム2の選手を配置する（未実装） -->
  <div id="team2" class="team-container">
    <div class="player7" onclick="showPlayerInfo('Player 2-7')">7</div>
    <div class="row">
      <div class="player" onclick="showPlayerInfo('Player 2-6')">6</div>
      <div class="player" onclick="showPlayerInfo('Player 2-5')">5</div>
      <div class="player" onclick="showPlayerInfo('Player 2-4')">4</div>
    </div>
    <div class="row">
      <div class="player" onclick="showPlayerInfo('Player 2-3')">3</div>
      <div class="player" onclick="showPlayerInfo('Player 2-2')">2</div>
      <div class="player" onclick="showPlayerInfo('Player 2-1')">1</div>
    </div>
  </div>
</div>

<!-- チーム1の選択ボックス -->
<select id="teamSelectLeft" onchange="updateTeamFormation('team1')">
  <option value="3-3-1">3-3-1</option>
  <option value="3-3-1">3-2-2</option>
  <option value="3-3-1">2-4-1</option>
  <option value="3-3-1">2-3-2</option>
  <!-- 他のフォーメーションも選択肢として追加 -->
</select>

<!-- チーム2の選択ボックス -->
<select id="teamSelectRight" onchange="updateTeamFormation('team2')">
  <option value="3-3-1">3-3-1</option>
  <option value="3-3-1">3-2-2</option>
  <option value="3-3-1">2-4-1</option>
  <option value="3-3-1">2-3-2</option>
  <!-- 他のフォーメーションも選択肢として追加 -->
</select>

<!-- モーダル -->
<div id="playerModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeModal()">&times;</span>
    <h2>選手情報</h2>
    <p id="playerInfo"></p>
    <label for="feature">利き足:</label>
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
</div>

<!-- JavaScript -->
<script>

// ページ読み込み時にモーダルを非表示にする
window.onload = function() {
  closeModal();
};

// 選手をクリックした際にモーダルを表示する関数
function showPlayerInfo(player) {
  var modal = document.getElementById("playerModal");
  var playerInfo = document.getElementById("playerInfo");
  playerInfo.innerHTML = "選手名: " + player; // ここで選手の情報を表示する
  modal.style.display = "block";
}

// モーダルを閉じる関数
function closeModal() {
  var modal = document.getElementById("playerModal");
  modal.style.display = "none";
}

// 選手情報を保存する関数
function savePlayer() {
  var foot = document.getElementById("foot").value;
  var goals = document.getElementById("goals").value;
  var feature = document.getElementById("feature").value;

  // 保存された情報を使って何かをする（例えば、データベースに保存するなど）
  
  // モーダルを閉じる
  closeModal();
}



  function updateTeamFormation(teamId) {
    var selectedFormation = document.getElementById(teamId === 'team1' ? 'teamSelectLeft' : 'teamSelectRight').value;
    var teamContainer = document.getElementById(teamId);
    teamContainer.innerHTML = ''; // プレイヤー配置をリセット

    if (selectedFormation === '3-3-1') {
      var players = ['Player 1', 'Player 2', 'Player 3', 'Player 4', 'Player 5', 'Player 6', 'Player 7'];
      var row1 = document.createElement('div');
      row1.classList.add('row');
      var row2 = document.createElement('div');
      row2.classList.add('row');
      var row3 = document.createElement('div');
      row3.classList.add('row');

      for (var i = 0; i < 1; i++) {
        var playerDiv = createPlayerDiv(players[i]);
        row1.appendChild(playerDiv);
      }

      for (var i = 1; i < 4; i++) {
        var playerDiv = createPlayerDiv(players[i]);
        row2.appendChild(playerDiv);
      }

      for (var i = 4; i < 7; i++) {
        var playerDiv = createPlayerDiv(players[i]);
        row3.appendChild(playerDiv);
      }

      teamContainer.appendChild(row1);
      teamContainer.appendChild(row2);
      teamContainer.appendChild(row3);
    } else if (selectedFormation === '3-2-2') {
      // 3-2-2の配置に変更
    } else if (selectedFormation === '2-4-1') {
      // 2-4-1の配置に変更
    } else if (selectedFormation === '2-3-2') {
      // 2-3-2の配置に変更
    }
  }

  // プレイヤーのdiv要素を作成する関数
  function createPlayerDiv(player) {
    var playerDiv = document.createElement('div');
    playerDiv.classList.add('player');
    playerDiv.textContent = player;
    playerDiv.onclick = function() {
      showPlayerInfo(player);
    };
    return playerDiv;
  }

</script>

</body>
</html>
