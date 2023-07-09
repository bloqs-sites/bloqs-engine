/* https://stackoverflow.com/a/55292366 */
// Helper function to detect if a string contains another string
//     at a specific position.
// Equivalent to using `str.indexOf(substr, pos) === pos` but *should* be more efficient on longer strings as it can exit early (needs benchmarks to back this up).
function hasSubstringAt(str: string , substr: string, pos: number) {
    var idx = 0, len = substr.length;

    for (var max = str.length; idx < len; ++idx) {
        if ((pos + idx) >= max || str[pos + idx] != substr[idx])
            break;
    }

    return idx === len;
}

export function trim(str: string, word: string) {
    var start = 0,
        end = str.length,
        len = word.length;

    while (start < end && hasSubstringAt(str, word, start))
        start += word.length;

    while (end > start && hasSubstringAt(str, word, end - len))
        end -= word.length

    return (start > 0 || end < str.length) ? str.substring(start, end) : str;
}
