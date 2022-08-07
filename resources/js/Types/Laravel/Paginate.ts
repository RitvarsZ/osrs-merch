export interface Paginate<T> {
  current_page: number;
  data: T[];
  first_page_url: string;
  last_page: number;
  last_page_url: string;
  prev_page_url: string|null;
  next_page_url: string|null;
  from: number;
  to: number;
  links: [{
    active: boolean,
    label: string,
    url: string|null
  }],
  path: string;
  per_page: number;
  total: number;
}
