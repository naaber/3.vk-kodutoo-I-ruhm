var $grid;

$(function(){
	getNews();
	$grid = $('#feed').isotope({
		itemSelector: '.item'
	});
});

var getNews = function(){
	$.ajax({
		url: "getFeed.php",
		success: function(result){
			printNews(JSON.parse(result).articles);
		},
		error: function(xhr, status, error){
			console.log(error);
		}
	});
};

var printNews = function(ajaxArticles){
	var items = '';
	$.each(ajaxArticles, function(index, article){
		items += '<div class="item">' + article.replace(/\"/g, "") + '</div>';
	});
	var temp = $(items);
  $grid.prepend(temp)
       .isotope('prepended', temp)
       .isotope('layout');
};
