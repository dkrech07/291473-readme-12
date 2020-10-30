const addingPostFilterElement = document.querySelector(`.adding-post__tabs`);
const addingPostButtonElements = addingPostFilterElement.querySelectorAll(`.adding-post__tabs-link`);

addingPostButtonElements.forEach((item) => {

  item.addEventListener('click', (evt) => {
    window.location.href = item.href;
  });
});
