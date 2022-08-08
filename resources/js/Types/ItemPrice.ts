export interface ItemPrice {
  id: number;
  item_id: number;
  high: number|null;
  high_time: string|null;
  low: number|null;
  low_time: string|null;
  created_at: string;
  updated_at: string;
};
