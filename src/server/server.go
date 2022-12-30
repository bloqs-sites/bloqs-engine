//go:build ignore

package main

import (
	"fmt"
	"log"
	"net/http"
	"math/rand"
	"time"
)

func handler(w http.ResponseWriter, r *http.Request) {
	fmt.Fprintf(w, "Hi there, I love %s!", r.URL.Path[1:])
}

func randHandler(w http.ResponseWriter, r *http.Request) {
	rand.Seed(time.Now().Unix())

	fmt.Fprintf(w, "%d", rand.Intn(5) + 1)
}

func main() {
	http.HandleFunc("/rand/", randHandler)
	http.HandleFunc("/", handler)
	log.Fatal(http.ListenAndServe(":8080", nil))
}
