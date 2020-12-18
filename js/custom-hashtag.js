const hashtagsContainerElement = document.querySelector(`.post__tags`);
const hashtagsElements = hashtagsContainerElement.querySelectorAll(`a`);

hashtagsElements.forEach(element => {
    element.href = '/search.php?q=' + encodeURIComponent(`#`) + element.innerHTML;
});