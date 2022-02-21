'use strict'
{
  // 夢日記公開タグ付け
  const release = document.getElementById('release');
  const tags = document.getElementById('tags');
  release.addEventListener('click', () => {
    release.children[0].classList.toggle('cancel')
    tags.classList.toggle('show');
  });
}