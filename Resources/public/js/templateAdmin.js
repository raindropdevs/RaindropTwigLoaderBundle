var templateAdmin = (function () {
    return {
        init: function () {
            this.initCodeMirror();
        },

        initCodeMirror: function () {
            var mixedMode = {
                name: "htmlmixed",
                scriptTypes: [
                    {
                        matches: /\/x-handlebars-template|\/x-mustache/i,
                        mode: null
                    },
                    {
                        matches: /(text|application)\/(x-)?vb(a|script)/i,
                        mode: "vbscript"
                    }
                ]
            };

            var el = $(".raindropTemplateTextarea");

            var editor = CodeMirror.fromTextArea(el.get(0), {
                lineNumbers: true,
                matchBrackets: true,
                mode: mixedMode,
                theme: 'solarized light',
                viewportMargin: Infinity
            });

            editor.setSize(el.width() - 200, 500);
        }
    };
}());

$(function () {
    templateAdmin.init();
})