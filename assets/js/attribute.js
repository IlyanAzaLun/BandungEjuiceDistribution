function currency(Num) {
    return new Intl.NumberFormat('en-EN', { maximumSignificantDigits: 18 }).format(Num);
}
function currencyToNum(curent) {
    return curent.replace(/[,]|[.]/g, '');
}
function shorttext(text, count, insertDots) {
    return text.slice(0, count) + (((text.length > count) && insertDots) ? "..." : "");
}
function shorttextfromback(text, count, insertDots) {
    var dot = (insertDots) ? '...' : '';
    return `${dot} ${text.slice(text.length - count)}`;
}