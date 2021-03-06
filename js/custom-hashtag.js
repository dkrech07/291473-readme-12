const HASHTAG_NUMBER = 1;
const SEARCH_LINK = `/search.php?q=`;

const hashtagsContainerElement = document.querySelector(`.post__tags`);
const hashtagsElements = hashtagsContainerElement.querySelectorAll(`a`);

hashtagsElements.forEach(element => {
    element.href = SEARCH_LINK + encodeURIComponent(`#`) + element.innerHTML.slice(HASHTAG_NUMBER);
});