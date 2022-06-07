function currency(Num) {
    return new Intl.NumberFormat('en-EN', { maximumSignificantDigits: 18 }).format(Num);
}
function currencyToNum(curent) {
    return curent.replace(/[,]|[.]/g, '');
}
function shorttext(text, count, insertDots) {
    return text ? text.slice(0, count) + (((text.length > count) && insertDots) ? "..." : "") : null;
}
function shorttextfromback(text, count, insertDots) {
    var dot = (insertDots) ? '...' : '';
    return text ? `${dot} ${text.slice(text.length - count)}` : null;
}
function formatDate(text) {
    return moment(text).format('DD-MM-YYYY HH:mm:ss');
}
