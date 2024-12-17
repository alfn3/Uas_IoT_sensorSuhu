<!DOCTYPE html>
<html lang="en">

<head>
  <title>Praktikum IoT</title>
  <link rel="stylesheet" href="./assets/styles.css" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="./assets/chart.min.js" type="text/javascript"></script>
</head>

<body>
  <div class="container">
    <!-- Sensors Section -->
    <div class="ui--screen-section">
      <div class="ui--section-title">
        <span>Sensors</span>
      </div>
      <div class="ui--horizontal-scroll">
        <div class="ui--card ui--card-square">
          <span class="ui-card--title">Temperature</span>
          <div class="value-unit-container">
            <span class="ui-card--value" id="temp-value">--</span>
            <span class="suffix">°C</span>
          </div>
        </div>
        <div class="ui--card ui--card-square">
          <span class="ui-card--title">Humidity</span>
          <div class="value-unit-container">
            <span class="ui-card--value" id="humd-value">--</span>
            <span class="suffix">%</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Temperature Indicator Section -->
    <div class="ui--screen-section">
      <div class="ui--section-title">
        <span>Temperature & Humidity Indicator</span>
      </div>
      <div class="ui--horizontal-scroll">
    <?php $indikator = array(4, 5, 6); ?>
    <?php foreach ($indikator as $key => $value): ?>
        <div class="ui--card ui--card-square" id="indicator-<?= $value ?>"> <!-- ID card dinamis -->
            <span class="ui-card--title">
                <?php
                if ($value == 4) echo "29°C - 30°C (beep 1x)";
                if ($value == 5) echo "30°C - 31°C (beep 2x)";
                if ($value == 6) echo "Diatas 31°C (beep 3x)";
                ?>
            </span>
            <div class="value-unit-container">
                <div class="ui-card--icon icon-lightbulb" id="gambar-lampu<?= $value ?>"></div>
            </div>
            <div class="ui--toggle" id="red" style="display: none;"> 
                <input type="checkbox" id="lampu<?= $value ?>" disabled />
                <label for="lampu<?= $value ?>">Toggle</label>
            </div>
        </div>
    <?php endforeach; ?>
    <?php $indikatorhumd = array(1, 2, 3); ?>
    <?php foreach ($indikatorhumd as $keyHumd => $valuehumd): ?>
        <div class="ui--card ui--card-square" id="indicatorHumd-<?= $valuehumd ?>"> <!-- ID card dinamis -->
            <span class="ui-card--title">
                <?php
                if ($valuehumd == 1) echo "30% - <60%<br>Aman";
                if ($valuehumd == 2) echo "60% - <70%<br>Normal (beep 1x)";
                if ($valuehumd == 3) echo "diatas 70%<br>Lembab (beep 3x)";
                ?>
            </span>
            <div class="value-unit-container">
                <div class="ui-card--icon icon-lightbulb" id="gambar-lampuHumd<?= $valuehumd ?>"></div>
            </div>
            <div class="ui--toggle" id="red" style="display: none;"> 
                <input type="checkbox" id="lampuHumd<?= $value ?>" disabled />
                <label for="lampuHumd<?= $value ?>">Toggle</label>
            </div>
        </div>
    <?php endforeach; ?>
</div>

    </div>

    <!-- Chart Section -->
    <div class="ui--screen-section">
      <div class="ui--section-title">
        <span>Chart</span>
      </div>
      <div class="ui--horizontal-scroll">
        <div style="width: 100%; margin: auto;">
          <canvas id="tempHumidityChart"></canvas>
        </div>
      </div>
    </div>

    <!-- Data Log Section -->
    <div class="ui--screen-section">
      <div class="ui--section-title">
        <span>Data Log</span>
      </div>
      <div class="ui--horizontal-scroll">
        <table class="table table-bordered">
          <thead>
            <tr class="table-primary">
              <th>No.</th>
              <th>Temperature</th>
              <th>Humidity</th>
              <th>Timestamp</th>
            </tr>
          </thead>
          <tbody id="table-body"></tbody>
        </table>
      </div>
    </div>

    <!-- Devices Section -->
    <div class="ui--screen-section">
      <div class="ui--section-title">
        <span>Devices</span>
      </div>
      <div class="ui--stack">
        <?php for ($i = 1; $i <= 3; $i++): ?>
          <div class="ui--card">
            <div class="ui-card--icon icon-lightbulb" id="gambar-lampu<?= $i ?>"></div>
            <div>
              <span class="ui-card--title">Lampu <?= $i ?></span>
              <span class="ui-card--subtitle" id="status-lampu<?= $i ?>">Off</span>
            </div>
            <div class="ui--toggle">
              <input type="checkbox" id="lampu<?= $i ?>" onchange="controlLED();updateUI();" />
              <label for="lampu<?= $i ?>">Toggle</label>
            </div>
          </div>
        <?php endfor; ?>
      </div>
    </div>
  </div>

  <!-- Audio Files -->
  <audio id="beep1" src="./assets/beep1.mp3"></audio>
  <audio id="beep2" src="./assets/beep2.mp3"></audio>
  <audio id="beep3" src="./assets/beep3.mp3"></audio>

  <!-- Modal -->
  <div class="modal fade" id="audioModal" tabindex="-1" aria-labelledby="audioModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="audioModalLabel">Konfirmasi Pemutaran Audio</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Klik "Play" untuk mengizinkan pemutaran audio.
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" id="playAudioButton" class="btn btn-primary">Play</button>
        </div>
      </div>
    </div>
  </div>

  <script src="./assets/script.js"></script>
</body>

</html>
