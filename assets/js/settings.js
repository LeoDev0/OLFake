// Trocando foto de perfil
const changePhotoBtn = document.getElementById("change-photo-btn");
const submitPhotoElem = document.querySelector(".submit-profile-photo");

// Ao clicar no elemento com a foto do perfil, um outro evento de clique
// é iniciado no elemento de envio de arquivos que está escondido na barra de navegação
changePhotoBtn.onclick = () => {
  submitPhotoElem.click();
};

// Validação da confirmação de senha
const input_nova_senha = document.getElementById("nova_senha");
const input_confirma_senha = document.getElementById("confirma_senha");
const dados_form = document.getElementById("dados_form");

dados_form.onsubmit = () => {
  if (input_nova_senha.value != input_confirma_senha.value) {
    alert("Senhas digitadas não são iguais!");
    return false;
  }
};
