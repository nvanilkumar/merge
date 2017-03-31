
$(document).ready(function () {
    var tags = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: api_tagsList + '?access_token=930332c8a6bf5f0850bd49c1627ced2092631250&limit=5',
            headers: {'Content-Type': 'application/x-www-form-urlencoded',
                'Authorization': 'bearer 930332c8a6bf5f0850bd49c1627ced2092631250'},
            replace: function (url, query) {
                //console.log(url+'---'+query);
                return url + "&keyword=" + query;
            },
            filter: function (res) {
                //console.log(res.response.total);
                var data = null;
                if (res.response.total > 0) {
//                    console.log(res.response.tags);
                    data = res.response.tags;
                }
                return data;
//                 if (res.response.total > 0) {
//                     data = res.response.tags;
//       $.map(data, function(value) {
//          console.log(value);
//            return { name: value.name,id:value.id }; 
//        });
//            }
            }
        }
    });
    //tags.initialize();

    $('#event_tags').tagsinput({
        confirmKeys: [13, 44],
//        itemValue: 'id',
//        itemText: 'name',
//        typeaheadjs: {
//            hint: true,
//            highlight: true,
//            minLength: 1,
//            name: 'tags',
//            displayKey: 'name',
//            valueKey: 'name',
//            source: tags.ttAdapter()
//        }
    });
    $('#event_tags').on('itemAdded', function (event) {
        var tags = $('#event_tags').tagsinput('items');
        if (tags.length > 10) {
            $('#event_tags_error').text("Reached max 10 limit!!!");
            $('#event_tags').tagsinput('remove', tags[10]);
        } else {
            $('#event_tags_error').text("");
        }
        $('#event_tags').tagsinput('focus');
    });
    $('#event_tags').on('itemRemoved', function (event) {
        var tags = $('#event_tags').tagsinput('items');
        if (tags.length < 10) {
            $('#event_tags_error').text("");
        }
        $('#event_tags').tagsinput('focus');
    });
    $('#event_tags').on('beforeItemAdd', function (event) {
        $('#event_tags_error').text('');
       var pattern = /^[\w_@&\s\-\.\\\/+]*$/;
       var pattern2 = /^[_@&\s\-\.\\\/+]*$/;
        if (!pattern.test(event.item) || pattern2.test(event.item)){
            event.cancel = true;
            $('#event_tags_error').text("Please enter valid tag name with words and _ , @ , & , - , . ,+, \\ , /");
            $('#event_tags').tagsinput('focus');
        }
    });
//       $('#event_tags').typeahead(null, {
//    displayKey: 'num',
//    source: [
//    { num: 'one' },
//    { num: 'two' },
//    { num: 'three' },
//    { num: 'four' },
//    { num: 'five' },
//    { num: 'six' },
//    { num: 'seven' },
//    { num: 'eight' },
//    { num: 'nine' },
//    { num: 'ten' }
//    ]
//    });
//
});