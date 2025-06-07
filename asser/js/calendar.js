let display = document.querySelector(".display");
let days = document.querySelector(".days");
let previous = document.querySelector(".left");
let next = document.querySelector(".right");

let date = new Date();

let year = date.getFullYear();
let month = date.getMonth();

function displayCalendar() {
  const firstDay = new Date(year, month, 1);

  const lastDay = new Date(year, month + 1, 0);

  const firstDayIndex = firstDay.getDay(); //4

  const numberOfDays = lastDay.getDate(); //31

  let formattedDate = date.toLocaleString("en-US", {
    month: "long",
    year: "numeric"
  });

  display.innerHTML = `${formattedDate}`;

  for (let x = 1; x <= firstDayIndex; x++) {
    const div = document.createElement("div");
    div.innerHTML += "";

    days.appendChild(div);
  }

  for (let i = 1; i <= numberOfDays; i++) {
    let div = document.createElement("div");
    let currentDate = new Date(year, month, i);

    div.dataset.date = currentDate.toDateString();

    div.innerHTML += i;
    days.appendChild(div);
    if (
      currentDate.getFullYear() === new Date().getFullYear() &&
      currentDate.getMonth() === new Date().getMonth() &&
      currentDate.getDate() === new Date().getDate()
    ) {
      div.classList.add("current-date");
    }
  }
}

// Call the function to display the calendar
displayCalendar();

previous.addEventListener("click", () => {
  days.innerHTML = "";

  if (month < 0) {
    month = 11;
    year = year - 1;
  }
  month = month - 1;
  date.setMonth(month);
  displayCalendar();
});

next.addEventListener("click", () => {
  days.innerHTML = "";

  if (month > 11) {
    month = 0;
    year = year + 1;
  }

  month = month + 1;
  date.setMonth(month);

  displayCalendar();
});
