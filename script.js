// Добавление загруженного изображения на странице добавления лота
document.addEventListener('DOMContentLoaded', () => {
  if (document.querySelector(`.form__input-file input[type="file"]`)) {
    const fileInput = document.querySelector(`.form__input-file input[type="file"]`);
    const image = document.querySelector(".form__file-button");

    fileInput.addEventListener("change", function () {
      if (this.files[0]) {
        const fr = new FileReader();

        fr.addEventListener("loadend", function () {
          image.style.backgroundImage = "url(" + fr.result + ")";
        }, false);

        fr.readAsDataURL(this.files[0]);
      }
    });
  }
});
