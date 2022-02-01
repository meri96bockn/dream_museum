'use strict'
{
  // 夢日記タグづけ 切り替え
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


  // マイページタブメニュー 切り替え
  const tab_titles = document.querySelectorAll('.tab li a');
  const contents = document.querySelectorAll('.content1, .content2')

  tab_titles.forEach(tab_title => {
    tab_title.addEventListener('click', e => {
      e.preventDefault();

      tab_titles.forEach(tab_title => {
        tab_title.classList.remove('active')
      });
      tab_title.classList.add('active');

      contents.forEach(content => {
        content.classList.remove('active');
      });
      document.getElementById(tab_title.dataset.id).classList.add('active')
    });
  });



  // むかしの夢 揺れる矢印
  window.onload=function(){
    const scroll = document.querySelector('.scroll');

    const Animation = function() {
      const triggerMargin = 100;
      if (window.innerHeight > scroll.getBoundingClientRect().top + triggerMargin) {
      scroll.classList.add('show');
      }
    }

    window.addEventListener('scroll', Animation);

    function fadeOut() {
      scroll.classList.add('fade-out');
    }
    setTimeout(fadeOut, 2000);
  }

}