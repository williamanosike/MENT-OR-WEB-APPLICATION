const form = document.querySelector(".typing-area");
const incoming_id = form.querySelector(".incoming_id").value;
const inputField = form.querySelector(".input-field");
const documentInput = form.querySelector(".document-input");
const sendBtn = form.querySelector("button");
const chatBox = document.querySelector(".chat-box");
const emojiPicker = document.querySelector(".emoji-picker");

form.onsubmit = (e) => {
  e.preventDefault();
};

inputField.focus();
inputField.onkeyup = () => {
  if (inputField.value != "") {
    sendBtn.classList.add("active");
  } else {
    sendBtn.classList.remove("active");
  }
};

sendBtn.onclick = () => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/insert-chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        inputField.value = "";
        documentInput.value = "";
        scrollToBottom();
      }
    }
  };
  let formData = new FormData();
  formData.append("incoming_id", incoming_id);
  formData.append("message", inputField.value);
  formData.append("document", documentInput.files[0]);
  xhr.send(formData);
};

chatBox.onmouseenter = () => {
  chatBox.classList.add("active");
};

chatBox.onmouseleave = () => {
  chatBox.classList.remove("active");
};

setInterval(() => {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "php/get-chat.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response;
        chatBox.innerHTML = data;
        if (!chatBox.classList.contains("active")) {
          scrollToBottom();
        }
      }
    }
  };
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhr.send("incoming_id=" + incoming_id);
}, 500);

function scrollToBottom() {
  chatBox.scrollTop = chatBox.scrollHeight;
}

// Emoji picker initialization
inputField.addEventListener("focus", () => {
  emojiPicker.style.display = "none";
});

inputField.addEventListener("click", () => {
  emojiPicker.style.display = "none";
});

inputField.addEventListener("keydown", (event) => {
  if (event.key === ":" && !event.shiftKey) {
    emojiPicker.style.display = "block";
    emojiPicker.focus();
    event.preventDefault();
  }
});

emojiPicker.addEventListener("emoji-click", (event) => {
  inputField.value += event.detail.emoji;
});
