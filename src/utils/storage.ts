import { openDB } from 'idb'

class storage {
  dbPromise: Promise<any>
  dbname: string

  constructor(dbname?: string) {
    this.dbname = dbname ?? 'mashiro'
    this.dbPromise = this.open()
  }

  open() {
    const { dbname } = this
    return openDB(dbname, 1, {
      upgrade(db) {
        db.createObjectStore(dbname)
      },
    })
  }

  async get<K>(key: K) {
    const { dbPromise, dbname } = this
    return (await dbPromise).get(dbname, key)
  }

  async set<K, V>(key: K, val: V, cache?: number) {
    const { dbPromise, dbname } = this
    return (await dbPromise).put(dbname, val, key)
  }

  async del<K>(key: K) {
    const { dbPromise, dbname } = this
    return (await dbPromise).delete(dbname, key)
  }

  async clear() {
    const { dbPromise, dbname } = this
    return (await dbPromise).clear(dbname)
  }

  async keys() {
    const { dbPromise, dbname } = this
    return (await dbPromise).getAllKeys(dbname)
  }
}

export default storage
