// Animação 'enviar oferta'
const send_offer_btn = document.getElementById("send-offer");
const animation_icon = document.getElementById("animation-icon");
const confirmation_message_elem = document.querySelector(
  ".confirmation-message"
);

send_offer_btn.onclick = () => {
  animation_icon.classList.remove("d-none");

  setTimeout(() => {
    confirmation_message_elem.classList.remove("d-none");
    send_offer_btn.classList.add("d-none");
  }, 2000);
};

// Efeito de zoom na foto do produto
const data_zoom_element = document.querySelectorAll("[data-zoom]");

data_zoom_element.forEach((element) => {
  element.onmousemove = (event) => {
    const { clientX: x, clientY: y } = event;

    element.style.transform = "scale(2)";
    element.style.transformOrigin = `${x}px ${y}px`;
  };
});

data_zoom_element.forEach((element) => {
  element.onmouseleave = () => {
    element.style.transform = "scale(1)";
    element.style.transformOrigin = "";
  };
});
