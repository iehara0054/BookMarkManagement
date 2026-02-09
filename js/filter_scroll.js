// [レビュー指摘:低] filter_scroll.js, unfilter_scroll.js, scroll_after_deletion.js の3ファイルが完全に同一の内容。1つに統合すべき
document.querySelector('#listTpl').scrollIntoView({
                behavior: 'auto'
            });