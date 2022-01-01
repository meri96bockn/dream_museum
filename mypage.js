'use strict';

// 夢投稿一覧カレンダー

{
  const today = new Date();
  let year = today.getFullYear();
  let month = today.getMonth();


  // 先月分
  function getCalenderHead() {
    const dates = [];
    const d = new Date(year, month, 0).getDate();
    const n = new Date(year, month, 1).getDay();

    for (let i = 0; i < n; i++) {
      dates.unshift({
        date: d - i,
        isToday: false,
        isDisabled: true,
        hasDream: false,  //夢が投稿されたら
      })
    }

    return dates;
  }


  // 今月分
  function getCalenderBody() {
    const dates = [];
    const lastDate = new Date(year, month + 1, 0).getDate();

    for (let i = 1; i <= lastDate; i++) {
      dates.push({
        date: i,
        isToday: false,
        isDisabled: false,
        hasDream: true,  //夢が投稿されたら
      });
    }

    if (year === today.getFullYear() && month === today.getMonth()) {
      dates[today.getDate() - 1].isToday = true;
    }

    return dates;
  }


  // 来月分
  function getCalenderTail() {
    const dates = [];
    const lastDay = new Date(year, month + 1, 0).getDay();

    for (let i = 1; i < 7 - lastDay; i++) {
      dates.push({
        date: i,
        isToday: false,
        isDisabled: true,
        hasDream: false,  //夢が投稿されたら
      })
    }

    return dates;
  }


  // カレンダーが更新されて増えないようにする
  function clearCalendar() {
    const tbody = document.querySelector('tbody')
    
    while (tbody.firstChild) {
      tbody.removeChild(tbody.firstChild);
    }
  }


  // 年月表示
  function renderTitle() {
    const title = `${year}/${String(month + 1).padStart(2, '0')}`;
    document.getElementById('title').textContent = title;
  }


  // 日にち表記を週ごとに処理
  function renderWeeks() {
    const dates = [
      ...getCalenderHead(),
      ...getCalenderBody(),
      ...getCalenderTail(),
    ];
    const weeks = [];
    const weeksCount = dates.length / 7;
  
    for (let i = 0; i < weeksCount; i++) {
      weeks.push(dates.splice(0, 7));
    }
  
    weeks.forEach(week => {
      const tr = document.createElement('tr');
      week.forEach(date => {
        const td = document.createElement('td');
        
        td.textContent = date.date;
        if (date.isToday) {
          td.classList.add('today')
        }
        if (date.isDisabled) {
          td.classList.add('disabled')
        }
        if (date.hasDream) {
          td.classList.add('dream')
        }      
  
        tr.appendChild(td);
      });
      document.querySelector('tbody').appendChild(tr);
    });
  }


  // カレンダーを作る関数まとめ
  function creatCalendar() {
    clearCalendar();
    renderTitle();
    renderWeeks();
  }


  // 先月の矢印をクリックしたときの処理
  document.getElementById('prev').addEventListener('click', () => {
    month--;
    if (month < 0) {
      year--;
      month = 11;
    }

    creatCalendar();
  });


  // 来月の矢印をクリックしたときの処理
  document.getElementById('next').addEventListener('click', () => {
    month++;
    if (month > 11) {
      year++;
      month = 0;
    }

    creatCalendar();
  });


  // Todayをクリックしたときの処理
  document.getElementById('today').addEventListener('click', () => {
    year = today.getFullYear();
    month = today.getMonth();
    creatCalendar();
  });


  // 夢投稿をクリックしたら詳細表示されるように
  // document.getElementsByClassName('dream').addEventListener('click', () => {
  // });

  // TitleIDをクリックしたら年単位で表示されるように
  // document.getElementsById('Title').addEventListener('click', () => {
  // });


  // カレンダー作成処理実行
creatCalendar();
}