const FullScreenButtons = {
    getHtml: function () {
        return (
            '            <div class="buttons">\n' +
            '                <button onclick="history.back();" class="left-button">\n' +
            '                    <div class="button-text">Назад</div>\n' +
            '                </button>\n' +
            '                <button onclick="history.forward();" class="right-button">\n' +
            '                    <div class="button-text">Вперед</div>\n' +
            '                </button>\n' +
            '            </div>\n' +
            '            <style scoped>\n' +
            '                #fullscreenButtonsControl{\n' +
            '                    position:fixed;\n' +
            '                    bottom: 40px;\n' +
            '                    left: 50%;\n' +
            '                    transform: translateX(-50%);\n' +
            '                    z-index: 2;\n' +
            '                }\n' +
            '                #fullscreenButtonsControl .buttons{\n' +
            '                    display: flex;\n' +
            '                    justify-content: center;\n' +
            '                    align-items: center;\n' +
            '                }\n' +
            '                #fullscreenButtonsControl .buttons > button{\n' +
            '                    display: flex;\n' +
            '                    justify-content: center;\n' +
            '                    align-items: center;\n' +
            '                    line-height: 1;\n' +
            '                    padding: 15px 24px;\n' +
            '                    background-color: #000;\n' +
            '                    opacity: .5;\n' +
            '                    transition: opacity .5s;\n' +
            '                    cursor: pointer;\n' +
            '				     border: none;\n' +
            '                }\n' +
            '                #fullscreenButtonsControl .buttons > button:hover{\n' +
            '                    opacity: .7;\n' +
            '                }\n' +
            '                #fullscreenButtonsControl .buttons > button:active{\n' +
            '                    opacity: .9;\n' +
            '                }\n' +
            '                #fullscreenButtonsControl .left-button {\n' +
            '                    border-radius: 20px 0 0 20px;\n' +
            '                }\n' +
            '\n' +
            '                #fullscreenButtonsControl .right-button{\n' +
            '                    border-radius: 0 20px 20px 0;\n' +
            '                }\n' +
            '                #fullscreenButtonsControl .button-text{\n' +
            '                    font-size: 16px;\n' +
            '                    letter-spacing: 1px;\n' +
            '                    color: #fff;\n' +
            '                    user-select: none;\n' +
            '                }\n' +
            '            </style>')
    },
    addButtons: function () {
        let buttons = document.createElement("div")
        buttons.id = 'fullscreenButtonsControl'
        buttons.innerHTML = this.getHtml()
        document.body.append(buttons)
    }

}
FullScreenButtons.addButtons()