 
  function generate(year,monIndex){
    
    var months = year.getMonths();
    var s = '';
    var WEEK = '日一二三四五六'.split('');
    //for(var x=0,y=months.length;x<y;x++){
      var mm = months[monIndex];
      s += '<ul class="month"><div class="flex flex-center"><div class="mgr-20">&lt;</div><h3>'+mm.getMonth()+'月</h3><div class="mgl-20">&gt;</div></div>';
      var days = mm.getDays();
      var week = days[0].getWeek();
      for(var i=0;i<7;i++){
        s += '<li>'+WEEK[i]+'</li>';
      }
      for(var i=0;i<week;i++){
        s += '<li></li>';
      }
      for(var i=0,j=days.length;i<j;i++){
        var d = days[i];
        var dl = d.getLunar();
        var sf = d.getFestivals();
        var lf = dl.getFestivals();
        var fs = [];
        var jq = dl.getJieQi();
        if(jq){
          fs.push(jq);
        }
        for(var m=0,n=sf.length;m<n;m++){
          fs.push(sf[m]);
        }
        for(var m=0,n=lf.length;m<n;m++){
          fs.push(lf[m]);
        }
        s += '<li onClick="setDay('+year+','+mm.getMonth()+','+(i+1)+')">'+d.getDay()+'<br />';
        if(fs.length>0){
          s += '<i>'+fs.join(',')+'</i>';
        }else{
          if(1==dl.getDay()){
            s += '<b>'+dl.getMonthInChinese()+'月</b>';
          }else{
            s += '<b>'+dl.getDayInChinese()+'</b>';
          }
        }
        var h = HolidayUtil.getHoliday(d.toYmd());
        if(h){
          s += '<u';
          if(h.isWork()){
            s += ' class="work"';
          }
          s += '>'+(h.isWork()?'班':'休')+'</u>';
        }
        s += '</li>';
      }
      s += '</ul>';
    //}
    document.getElementById('guali').innerHTML = s;
  }
  function setDay(y,m,i){
	  console.log(y,m,i)
	  m=m<10?'0'+m:''+m;
	  i=i<10?'0'+i:''+i;
	  App.setDate(y+"-"+m+"-"+i);
  }
 