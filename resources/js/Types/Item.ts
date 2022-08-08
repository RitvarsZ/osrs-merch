import type { ItemPrice } from "./ItemPrice";

export interface Item {
  id: number;
  item_id: number;
  name: string;
  examine: string;
  icon: string;
  members: boolean;
  lowalch: number;
  highalch: number;
  limit: number;
  latest_price?: ItemPrice
  created_at: string;
  updated_at: string;
};
