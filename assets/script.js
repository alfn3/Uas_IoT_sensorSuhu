const chart = new Chart(
  document.getElementById("tempHumidityChart").getContext("2d"),
  {
    type: "line",
    data: {
      labels: [],
      datasets: [
        {
          label: "Temperature (°C)",
          data: [],
          borderColor: "rgba(255, 99, 132, 1)",
          backgroundColor: "rgba(255, 99, 132, 0.2)",
          borderWidth: 2,
          fill: true,
        },
        {
          label: "Humidity (%)",
          data: [],
          borderColor: "rgba(54, 162, 235, 1)",
          backgroundColor: "rgba(54, 162, 235, 0.2)",
          borderWidth: 2,
          fill: true,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
          position: "top",
          labels: {
            color: 'white', // Change the legend text color to white
          },
        },
        tooltip: {
          enabled: true,
        },
      },
      scales: {
        x: {
          title: {
            display: true,
            text: "Time",
            color: 'white', // Change the x-axis title color to white
          },
          ticks: {
            color: 'white', // Change the x-axis tick color to white
          },
        },
        y: {
          title: {
            display: true,
            text: "Value",
            color: 'white', // Change the y-axis title color to white
          },
          ticks: {
            color: 'white', // Change the y-axis tick color to white
          },
          beginAtZero: true,
        },
      },
    },
  }  
);

function setTemp(value) {
  document.getElementById("temp-value").innerHTML = value;
}

function setHumd(value) {
  document.getElementById("humd-value").innerHTML = value;
}

function turnLedUiOn(lampId, color = "") {
  document.getElementById(`status-lampu${lampId}`).innerHTML = "On";
  document.getElementById(
    `gambar-lampu${lampId}`
  ).className = `ui-card--icon icon-lightbulb-on`;
}

function turnLedUiOff(lampId) {
  document.getElementById(`lampu${lampId}`).checked = false;
  document.getElementById(`status-lampu${lampId}`).innerHTML = "Off";
  document.getElementById(`gambar-lampu${lampId}`).className =
    "ui-card--icon icon-lightbulb";
}

function ledIndicatorOn(lampId) {
  document.getElementById(
    `gambar-lampu${lampId}`
  ).className = `ui-card--icon icon-lightbulb-on-red`;
}

function ledIndicatorOff(lampId) {
  document.getElementById(
    `gambar-lampu${lampId}`
  ).className = `ui-card--icon icon-lightbulb`;
}

function indicatorOn(lampId) {
  document.getElementById(`indicator-${lampId}`).style.display = 'block';
}

function indicatorOff(lampId) {
  document.getElementById(`indicator-${lampId}`).style.display = 'none';
}

function turnLedUiHumdOn(lampIdHumd, color = "") {
  document.getElementById(`status-lampuHumd${lampIdHumd}`).innerHTML = "On";
  document.getElementById(
    `gambar-lampuHumd${lampIdHumd}`
  ).className = `ui-card--icon icon-lightbulb-on`;
}

function turnLedUiHumdOff(lampIdHumd) {
  document.getElementById(`status-lampuHumd${lampIdHumd}`).innerHTML = "Off";
  document.getElementById(`gambar-lampuHumd${lampIdHumd}`).className =
    "ui-card--icon icon-lightbulb";
}

function ledIndicatorHumdOn(lampIdHumd) {
  document.getElementById(
    `gambar-lampuHumd${lampIdHumd}`
  ).className = `ui-card--icon icon-lightbulb-on-red`;
}

function ledIndicatorHumdOff(lampIdHumd) {
  document.getElementById(
    `gambar-lampuHumd${lampIdHumd}`
  ).className = `ui-card--icon icon-lightbulb`;
}

function indicatorHumdOn(lampIdHumd) {
  document.getElementById(`indicatorHumd-${lampIdHumd}`).style.display = 'block';
}

function indicatorHumdOff(lampIdHumd) {
  document.getElementById(`indicatorHumd-${lampIdHumd}`).style.display = 'none';
}

function updateIndicator(temperature) {
  if (temperature > 29 && temperature < 30) {
    document.getElementById("beep1").play();
    ledIndicatorOn(4);
    indicatorOn(4);
    ledIndicatorOff(5);
    indicatorOff(5);
    ledIndicatorOff(6);
    indicatorOff(6);
  } else if (temperature >= 30 && temperature <= 31) {
    document.getElementById("beep2").play();
    ledIndicatorOff(4);
    indicatorOff(4);
    ledIndicatorOn(5);
    indicatorOn(5);
    ledIndicatorOff(6);
    indicatorOff(6);
  } else if (temperature > 31) {
    document.getElementById("beep3").play();
    ledIndicatorOff(4);
    indicatorOff(4);
    ledIndicatorOff(5);
    indicatorOff(5);
    ledIndicatorOn(6);
    indicatorOn(6);
  } else {
    ledIndicatorOff(4);
    indicatorOff(4);
    ledIndicatorOff(5);
    indicatorOff(5);
    ledIndicatorOff(6);
    indicatorOff(6);
  }
}

function updateIndicatorHumd(humidity) {
  if (humidity >= 30 && humidity < 60) {
    ledIndicatorHumdOn(1);
    indicatorHumdOn(1);
    ledIndicatorHumdOff(2);
    indicatorHumdOff(2);
    ledIndicatorHumdOff(3);
    indicatorHumdOff(3);
  } else if (humidity >= 60 && humidity <= 70) {
    document.getElementById("beep1").play();
    ledIndicatorHumdOn(1);
    indicatorHumdOff(1);
    ledIndicatorHumdOn(2);
    indicatorHumdOn(2);
    ledIndicatorHumdOff(3);
    indicatorHumdOff(3);
  } else if (humidity > 70) {
    document.getElementById("beep3").play();
    ledIndicatorHumdOn(1);
    indicatorHumdOff(1);
    ledIndicatorHumdOn(2);
    indicatorHumdOff(2);
    ledIndicatorHumdOn(3);
    indicatorHumdOn(3);
  } else {
    ledIndicatorHumdOff(1);
    indicatorHumdOff(1);
    ledIndicatorHumdOff(2);
    indicatorHumdOff(2);
    ledIndicatorHumdOff(3);
    indicatorHumdOff(3);
  }
}
const updateChart = (sensorsData) => {
  const reverse = sensorsData.reverse();
  const sensorsDataChart = {
    temperatures: reverse.map((sensor) => sensor.temperature),
    humidities: reverse.map((sensor) => sensor.humidity),
    timestamps: reverse.map((sensor) => sensor.timestamp),
  };
  chart.data.labels = sensorsDataChart.timestamps;
  chart.data.datasets[0].data = sensorsDataChart.temperatures;
  chart.data.datasets[1].data = sensorsDataChart.humidities;
  chart.update();
};

const updateTable = (sensorsData) => {
  const tableBody = document.getElementById("table-body");
  const tableRows = sensorsData
    .map((data, index) => {
      return `
        <tr>
          <td>${data["id"]}</td>
          <td>${data["temperature"]}</td>
          <td>${data["humidity"]}</td>
          <td>${data["timestamp"]}</td>
        </tr>`;
    })
    .join("\n");
  tableBody.innerHTML = tableRows;
};

const controlLED = async () => {
  const payload = {
    LED1: document.getElementById("lampu1").checked ? "on" : "off",
    LED2: document.getElementById("lampu2").checked ? "on" : "off",
    LED3: document.getElementById("lampu3").checked ? "on" : "off",
  };

  try {
    const response = await fetch("publish.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(payload),
    });

    const result = await response.json();
    // alert(result.message);
  } catch (error) {
    console.error("Error:", error);
    // alert("Terjadi kesalahan saat mengontrol LED.");
  }
};

const getData = async () => {
  const response = await fetch("subscribe.php");
  const data = await response.json();
  return data;
};

const updateUI = async () => {
  const { temperature, humidity, control, sensors_data } = await getData();
  setTemp(temperature);
  setHumd(humidity);
  updateIndicator(temperature);
  updateIndicatorHumd(humidity);
  updateChart(sensors_data);
  updateTable(sensors_data);
  for (let [key, value] of Object.entries(control)) {
    const lampId = key.replace("LED", "");
    if (value == "on") turnLedUiOn(lampId);
    if (value == "off") turnLedUiOff(lampId);
  }
  for (let [keyHumd, value] of Object.entries(control)) {
    const lampIdHumd = keyHumd.replace("LED", "");
    if (value == "on") turnLedUiHumdOn(lampIdHumd);
    if (value == "off") turnLedUiHumdOff(lampIdHumd);
  }
};

document.addEventListener("DOMContentLoaded", function () {
  const playButton = document.getElementById("playAudioButton");

  const modal = new bootstrap.Modal(document.getElementById("audioModal"));
  modal.show();

  playButton.addEventListener("click", () => {
    setInterval(() => {
      updateUI();
    }, 5000);
    updateUI();
    modal.hide();
  });
});
