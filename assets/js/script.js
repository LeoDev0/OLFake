const excluir_anuncio_btn = document.querySelectorAll("[data-confirm]");

// Popup de confirmação para deleção de um anúncio
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

// Trocando foto de perfil
const changePhotoBtn = document.getElementById("change-photo-btn");
const submitPhotoElem = document.querySelector(".submit-profile-photo");

// Ao clicar no elemento com a foto do perfil, um outro evento de clique
// é iniciado no elemento de envio de arquivos que está escondido na barra de navegação
changePhotoBtn.onclick = () => {
  submitPhotoElem.click();
};
