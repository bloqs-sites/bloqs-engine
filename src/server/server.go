package main

import (
	"bufio"
	"encoding/json"
	"fmt"
	"log"
	"math/rand"
	"net/http"
	"os"
	"time"

	"github.com/golang-jwt/jwt/v4"
	"github.com/torres-developer/bloqsenjin"
)

var (
	proxy *enjin.DriverProxy
)

const (
	ALL uint8 = 1
)

func init() {
	config, err := enjin.CreateConfig("../../config/credentials.json")

	if err != nil {
		panic(err)
	}

	proxy = config.CreateProxy()

	file, err := os.Open("../../config/preferences")

	if err != nil {
		panic(err)
	}

	defer file.Close()

	preferences := make([]enjin.Preference, 0)
	scanner := bufio.NewScanner(file)
	for scanner.Scan() {
		preferences = append(preferences, enjin.Preference(scanner.Text()))
	}

	proxy.InitiateDatabase(preferences)
}

func randHandler(w http.ResponseWriter, r *http.Request) {
	rand.Seed(time.Now().Unix())

	fmt.Fprintf(w, "%d", rand.Intn(5)+1)
}

func preferencesHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != "GET" {
		return
	}

	refs, err := proxy.GetPreferences(false)
	preferences := make([]enjin.Preference, len(refs))
	for i, v := range refs {
		preferences[i] = v
	}

	if err != nil {
		w.WriteHeader(http.StatusInternalServerError)
	}

	json, err := json.Marshal(preferences)

	if err != nil {
		w.WriteHeader(http.StatusInternalServerError)
	}

	fmt.Fprintf(w, "%s", string(json))
	w.Header().Add("Content-Type", "application/json")
}

func clientsHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != "POST" {
		return
	}

	var reqBody struct {
		Client *string   `json:"client"`
		Likes  *[]string `json:"likes"`
	}

	if err := json.NewDecoder(r.Body).Decode(&reqBody); err != nil {
		panic(err)
	}

	preferences := make([]enjin.Preference, len(*reqBody.Likes))

	for i, v := range *reqBody.Likes {
		preferences[i] = enjin.Preference(v)
	}

	err := proxy.CreateClient(*reqBody.Client, preferences)

	if err != nil {
		panic(err)
	}
}

func authHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method == "POST" {
		var reqBody struct {
			Client *string `json:"client"`
		}

		type claims struct {
			Client      string `json:"id"`
			Permissions uint8  `json:"auth"`
			jwt.RegisteredClaims
		}

		decoder := json.NewDecoder(r.Body)

		if err := decoder.Decode(&reqBody); err != nil {
			panic(err)
		}

		token := jwt.NewWithClaims(jwt.SigningMethodHS512, &claims{
			*reqBody.Client,
			ALL,
			jwt.RegisteredClaims{
				ExpiresAt: jwt.NewNumericDate(time.Now().Add(15 * time.Minute)),
				IssuedAt:  jwt.NewNumericDate(time.Now()),
				NotBefore: jwt.NewNumericDate(time.Now()),
				Issuer:    "bloqsenjin",
				Subject:   *reqBody.Client,
			},
		})

		tokenstr, err := token.SignedString([]byte("secret"))

		if err != nil {
			panic(err)
		}

		fmt.Fprintf(w, "%s", tokenstr)
	}
}

func main() {
	defer proxy.Close()

	http.HandleFunc("/auth", authHandler)

	http.HandleFunc("/clients", clientsHandler)
	http.HandleFunc("/preferences", preferencesHandler)

	log.Fatal(http.ListenAndServe(":8080", nil))
}
