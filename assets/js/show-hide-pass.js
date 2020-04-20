// Mostrar/Esconder as senha digitadas
const icon_elems = document.querySelectorAll("[data-icon-change]");
const password_inputs = document.querySelectorAll("[data-password-input]");
const show_hide_btns = document.querySelectorAll(".show-hide-pass");

show_hide_btns.forEach(
  (btn) =>
    (btn.onclick = () => {
      icon_elems.forEach((icon) => icon.classList.toggle("fa-eye-slash"));

      password_inputs.forEach((input) => {
        if (input.getAttribute("type") == "password") {
          input.setAttribute("type", "text");
        } else {
          input.setAttribute("type", "password");
        }
      });
    })
);
