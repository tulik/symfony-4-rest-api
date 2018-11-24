# Examples of Usage

**Notice:** Don't forget to add `Content-Type: application/json` to your requests.


**Get JWT token:**

```
{
	"username": "developer@symfony.local",
	"password": "developer"
}
```

**Get list of all books**

```
[GET] http://[host]/books
```

**Get second page the list**

```
[GET] http://[host]/books?page=2
```

By default if Request don't have`limit` parameter Response will return 10 results.

**Get 20 results per page**

```
[GET] http://[host]/books?limit=20
```

You can combine freely combine all available parameters.

```
[GET] http://[host]/books?limit=20&page=2
```

**Get books with its reviews**
You can also expand book listing of it's reviews.

```
[GET] http://[host]/books?expand=reviews
[GET] http://[host]/books?expand=reviews&limit=20&page=2
```

*Add a new book*

```
[POST] http://[host]/books

{           "isbn": "9799325573620",
            "title": "Accusamus nihil repellat vero omnis.",
            "description": "Amet et et suscipit qui recusandae totam. Quam ipsam voluptatem cupiditate sed natus debitis voluptas. Laudantium sit repudiandae esse perspiciatis dignissimos error et itaque. Tempora velit porro ut velit soluta explicabo eligendi.",
            "author": "Serena Streich",
            "publicationDate": "2008-05-10T01:29:03+00:00"
}

```

## Filtering
**Get Book of given ISBN**

```
[GET] http://[host]/books?book_filter[isbn]=9799325573620
```

ISBN is Book unique property - if book will not be find it return 404 error. 

**Get books published between certain date:**

```
[GET] http://[host]/books?book_filter[publicationDate][left_datetime]=2017-06-24&[publicationDate][right_datetime]=2018-06-24
```

**Get Users who watched movie of given title**

```
[GET] http://[host]/users?user_filter[movies]=Et aut esse.
[GET] http://[host]/users?expand=movies&user_filter[movies]=Et aut esse.
```
