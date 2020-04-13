// Popup de confirmação para deleção de um anúncio
const excluir_anuncio_btn = document.querySelectorAll("[data-confirm]");

excluir_anuncio_btn.forEach(
  (btn) =>
    (btn.onclick = () => {
      let deletar = confirm(
        "Tem certeza que deseja excluir este anúncio PERMANENTEMENTE?"
      );

      // Se o usuário der "Cancel", impede o envio da confirmação, se der "Ok", confirma a deleção e envia pro servidor
      if (deletar == false) {
        return false;
      }
    })
);

//  -------------------------------------------- //

// Trocando foto de perfil
const changePhotoBtn = document.getElementById("change-photo-btn");
const submitPhotoElem = document.querySelector(".submit-profile-photo");

// Ao clicar no elemento com a foto do perfil, um outro evento de clique
// é iniciado no elemento de envio de arquivos que está escondido na barra de navegação
changePhotoBtn.onclick = () => {
  submitPhotoElem.click();
};

//  -------------------------------------------- //

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
