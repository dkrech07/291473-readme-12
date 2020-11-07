window.custom = {
  getSorting : function(sortingElement) {
    sortingElement.forEach(function(item) {

      item.addEventListener('click', (evt) => {
        window.location.href = item.href;
      });
    });
  },
};
