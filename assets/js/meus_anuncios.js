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
