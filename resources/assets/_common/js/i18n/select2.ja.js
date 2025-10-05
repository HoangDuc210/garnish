setTimeout(() => {
  $.fn.select2.amd.define('select2/i18n/ja',[],function (){
    // Japanese
    return {
      errorLoading: function () {
        return '結果が読み込まれませんでした'; //English version: 'The results could not be loaded.'
      },
      inputTooLong: function (args) {
        var overChars = args.input.length - args.maximum;

        var message = overChars + ' 文字を削除してください'; //English version: 'Please delete N character(s)'

        return message;
      },
      inputTooShort: function (args) {
        var remainingChars = args.minimum - args.input.length;

        var message = '少なくとも ' + remainingChars + ' 文字を入力してください'; //English version: 'Please enter N or more characters'

        return message;
      },
      loadingMore: function () {
        return '読み込み中…'; //English version: 'Loading more results…'
      },
      maximumSelected: function (args) {
        var message = args.maximum + ' 件しか選択できません'; //English version: 'You can only select N item(s)'

        return message;
      },
      noResults: function () {
        return '該当なし'; //English version: 'No results found'
      },
      searching: function () {
        return '検索しています…'; //English version: 'Searching…'
      },
      removeAllItems: function () {
        return 'すべてのアイテムを削除'; //English version: 'Remove all items'
      }
    };
  });
}, 1000);
