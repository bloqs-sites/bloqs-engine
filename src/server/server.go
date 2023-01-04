package main

import (
	"bufio"
	"fmt"
	"log"
	"math/rand"
	"net/http"
	"os"
	"time"

	"github.com/torres-developer/bloqsenjin"
)

var (
	proxy *enjin.DriverProxy
)

func init() {
	config, err := enjin.CreateConfig("../../config/credentials.json")

	if err != nil {
		panic(err)
	}

	proxy = config.CreateProxy()

	preferences, err := os.Open("../../config/preferences")

	if err != nil {
		panic(err)
	}

	defer preferences.Close()

	scanner := bufio.NewScanner(preferences)
	for scanner.Scan() {
		go proxy.NewPreference(enjin.Preference(scanner.Text()))
	}
}

func handler(w http.ResponseWriter, r *http.Request) {
	fmt.Fprintf(w, "Hi there, I love %s!", r.URL.Path[1:])
}

func randHandler(w http.ResponseWriter, r *http.Request) {
	rand.Seed(time.Now().Unix())

	fmt.Fprintf(w, "%d", rand.Intn(5)+1)
}

func main() {
	fmt.Println(proxy.GetPreferences(true))
	http.HandleFunc("/rand/", randHandler)
	http.HandleFunc("/", handler)
	log.Fatal(http.ListenAndServe(":8080", nil))
}
