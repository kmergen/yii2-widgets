const KMCharCounter = (function () {
    const pub = {
        init: function init(options) {
            extend(options)
            elToCount = document.getElementById(settings.inputId)
            buildCounter()
            elToCount.addEventListener('keyup', function () {
                countChars()
            })
            countChars()
        }
    }

    function extend(options) {
        $.extend(settings, options)
    }

    let elToCount
    let elCounter
    const settings = {
        inputId: null,
        limit: 500,
        textLimitReached: 'Maximale Anzal von {n} Zeichen erreicht',
        textLimitNotReached: 'Maximale Anzal von {n} Zeichen erreicht',
        counterOptions: {
            tag: 'div',
            class: 'char-counter'
        }
    }

    function buildCounter() {
        elCounter = document.createElement(settings.counterOptions.tag)
        elCounter.setAttribute('id', settings.counterOptions.id)
        elCounter.setAttribute('class', settings.counterOptions.class)
        const selector = settings.selector ? document.querySelector(settings.selector) : elToCount
        selector.parentNode.insertBefore(elCounter, selector.nextSibling)
    }

    function countChars() {
        const elToCountLength = elToCount.value.length

        if (elToCountLength >= settings.limit) {
            elCounter.innerHTML = settings.textLimitReached.replace('{n}', settings.limit)
            elToCount.value = elToCount.value.substr(0, settings.limit)
        } else {
            elCounter.innerHTML = settings.textLimitNotReached.replace('{n}', settings.limit - elToCountLength)
        }
        return true
    }

    return pub
}())
