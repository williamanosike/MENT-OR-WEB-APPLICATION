const form = document.querySelector(".signup form");
const continueBtn = form.querySelector(".button input");
const errorText = form.querySelector(".error-text");

form.onsubmit = (e) => {
  e.preventDefault();
};

continueBtn.onclick = () => {
  const roleSelect = form.querySelector("select[name='role']");
  const selectedRole = roleSelect.value;

  if (selectedRole === "mentor") {
    window.location.href = "mentor.php";
  } else if (selectedRole === "mentee") {
    window.location.href = "mentee.php";
  }
};
