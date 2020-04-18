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
