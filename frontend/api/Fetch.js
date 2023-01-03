import { get } from "http";
import env from "./env";

const base = env.host.href;
let body = "data";

export class App {
  static user_session;

  static register(email, password){
    this.user_session = fetch(new URL("/api/register", base).href, {
        headers: {
            'Content-Type' : 'application/json'
        },
        method: 'post',
        [body]: JSON.stringify({email, password})
    })
    .then(res => res.json())
    .then(res => {
        localStorage.setItem('token_api', JSON.stringify(res));
        return res
      })
      .catch((rej) => {console.log(rej.response.data, rej)})
  }

  authentication(email, password){
    
    if ((Boolean(email) && Boolean(password)) || localStorage.getItem("token_api")) {
        this.user_session = !localStorage.token_api ? fetch(new URL('/api/login', base).href, {
          headers: {
            'Content-Type': `application/json`
          },  
          method: "post",
          [body]: JSON.stringify({ email, password }),
        })
          .then((res) => res.json())
          .then(res => {
            localStorage.setItem('token_api', JSON.stringify(res));
            return res
          })
          .catch((rej) => {console.log(rej.response?.data, rej)})
           : new Promise(res => res(JSON.parse(localStorage.token_api)))
            //   token suppose to be checked on future request.
           .then(async session => {
                const res = await fetch(new URL("/api/check_token", base).href, {
                   method: 'post',
                   headers: {
                       'Authorization': `Bearer ${session.token}`
                   }
               });
               const json = await res.json();

               if (json?.data) {
                return session;
               } else {
                localStorage.removeItem("token_api");
                this.authentication(email, password)
               }

               
           });
    }
  }

  constructor(email, password) {
   this.authentication(email, password);
  }

  query_fetch(endpoint, callback){
    return {
        get(query){
            return Boolean(this.user_session) && this.user_session.then(session => {
                const parsed = new URLSearchParams();
                for (const params in query) {
                    if (Object.hasOwnProperty.call(query, params)) {
                        const element = query[params];
                        parsed.set(params, element)
                    }
                }
                return fetch(new URL(`/api/${endpoint}?${parsed.toString()}`, base).href, {
                    headers: {
                        'Authorization': `Bearer ${session.token}`
                    }
                }).catch(rej => false).then(res => res.json())
            })
        },

        getAll(){

        },

        update(){

        },

        post(query){
            return Boolean(this.user_session) && this.user_session.then(session => {
                fetch(new URL(`/api/${endpoint}`, base).href, {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${session.token}`
                    },
                    [body]: JSON.stringify(query)
                })
            })
        },

        delete(){
            return Boolean(this.user_session) && this.user_session.then(session => {
        
            })
        }
    }
  }

  users() {
    return this.query_fetch("user")
  }

  wallets() {}

  category() {}

  transactions() {}
}