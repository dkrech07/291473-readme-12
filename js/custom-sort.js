(function () {
  const sortingPostsFilterElement = document.querySelector(`.popular__sorting-list.sorting__list`);
  const sortingPostsButtonElements = sortingPostsFilterElement.querySelectorAll(`.sorting__link`);

  window.custom.getSorting(sortingPostsButtonElements);
})();
