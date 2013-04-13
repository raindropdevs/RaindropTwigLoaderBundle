CodeMirror.defineMode("twig", function(config, parserConfig) {
  var twigOverlay = {
    token: function(stream, state) {
      var ch;
      if (stream.match("{%")) {
        while ((ch = stream.next()) != null) {
          if (ch == "%" && stream.next() == "}") break;
        }
        stream.eat("}");
        return "twig";
      }
      while (stream.next() != null && !stream.match("{%", false)) {}
      return null;
    }
  };
  return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || "text/html"), twigOverlay);
});