class Sessions {
  user_session;
  constructor(email, password) {
    
    this.user_session = token_api ? fetch("login", {
      method: "post",
      body: JSON.stringify({ email, password }),
    })
      .catch((rej) => {})
      .then((res) => res.json())
      .then(res => {
        localStorage.setItem('token_api', res.token)
        return res
      }) : JSON.parse(localStorage.token_api);
  }

  query_fetch(endpoint, callback){
    return {
        get(query){
            this.user_session.then(session => {
                const parsed = new URLSearchParams();
                for (const params in query) {
                    if (Object.hasOwnProperty.call(query, params)) {
                        const element = query[params];
                        parsed.set(params, element)
                    }
                }
                return fetch(`/api/${endpoint}?${parsed.toString()}`, {
                    headers: new Headers({
                        'Authorization': `Bearer`
                    })
                }).catch(rej => false).then(res => res.json())
            })
        },

        post(query){
            this.user_session.then(session => {
                fetch(`/api/${endpoint}`, {
                    method: 'POST',
                    body: JSON.stringify(query)
                })
            })
        },

        delete(){
            this.user_session.then(session => {
        
            })
        }
    }
  }

  users() {
    
  }

  wallets() {}

  category() {}

  transactions() {}
}

m