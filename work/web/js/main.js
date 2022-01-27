'use strict';

{
  // ハンバーガーメニューの表示切り替え
  const open = document.getElementById('open')
  const overlay = document.querySelector('.overlay');
  const close = document.getElementById('close');

  open.addEventListener('click', () => {
    overlay.classList.add('show');
    open.classList.add('hide');
  });

  close.addEventListener('click',  () => {
    overlay.classList.remove('show');
    open.classList.remove('hide');
  });


// 夢日記タグづけの表示切り替え
  const no_tag = document.getElementById('no_tag');
  const yes_tag = document.getElementById('yes_tag');
  const tags = document.getElementById('tags');
  const emotions = document.querySelectorAll('[name="emotion"]');

  no_tag.addEventListener('click', () => {
    tags.classList.remove('show');
    emotions.forEach(emotion => {
      emotion.disabled = true;
    })
  });

  yes_tag.addEventListener('click', () => {
    tags.classList.add('show');
    emotions.forEach(emotion => {
      emotion.disabled = false;
    })
  });

}