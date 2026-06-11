import { Component, OnInit } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { BookService, Book } from './services/book'; // Adjusted to find './services/book'!

@Component({
  selector: 'app-root',
  standalone: true,
  imports: [CommonModule, FormsModule],
  templateUrl: './app.html',
  styleUrl: './app.css'
})
export class App implements OnInit {
  books: Book[] = [];
  newBook: Book = { title: '', author: '', isbn: '', publishedYear: 2026 };

  constructor(private bookService: BookService) {}

  ngOnInit() {
    this.loadBooks();
  }

  loadBooks() {
    this.bookService.getBooks().subscribe((data: Book[]) => {
      this.books = data;
    });
  }

  onSubmit() {
    if (this.newBook.title && this.newBook.author) {
      this.bookService.addBook(this.newBook).subscribe(() => {
        this.loadBooks(); // Refresh the list from PostgreSQL
        this.newBook = { title: '', author: '', isbn: '', publishedYear: 2026 }; // Reset form
      });
    }
  }
}
