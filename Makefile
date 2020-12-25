all:
	if [[ -e charitable-begateway.zip ]]; then rm charitable-begateway.zip; fi
	zip -r charitable-begateway.zip charitable-begateway -x "*/test/*" -x "*/.git/*" -x "*/examples/*" -x "*.DS_Store*"
