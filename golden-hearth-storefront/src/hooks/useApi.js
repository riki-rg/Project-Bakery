import { useState, useEffect, useCallback, useRef } from 'react'

/**
 * Generic hook untuk API call dengan loading/error state
 * @param {Function} apiFn - fungsi dari services/api.js
 * @param {any[]} deps - dependencies untuk re-fetch
 * @param {object} options - { immediate: bool, params: object }
 */
export function useApi(apiFn, deps = [], options = {}) {
  const { immediate = true, params = null } = options
  const [data, setData] = useState(null)
  const [loading, setLoading] = useState(immediate)
  const [error, setError] = useState(null)
  const abortRef = useRef(null)

  const execute = useCallback(async (overrideParams) => {
    setLoading(true)
    setError(null)

    try {
      const res = await apiFn(overrideParams ?? params)
      setData(res.data?.data ?? res.data)
      return res.data?.data ?? res.data
    } catch (err) {
      const msg = err.response?.data?.message ?? err.message ?? 'Terjadi kesalahan.'
      setError(msg)
      return null
    } finally {
      setLoading(false)
    }
  }, deps) // eslint-disable-line react-hooks/exhaustive-deps

  useEffect(() => {
    if (immediate) execute()
  }, [execute]) // eslint-disable-line react-hooks/exhaustive-deps

  return { data, loading, error, refetch: execute }
}

/**
 * Hook untuk paginated list dengan filter/sort
 */
export function usePaginatedApi(apiFn, params = {}) {
  const [data, setData] = useState([])
  const [meta, setMeta] = useState(null)
  const [loading, setLoading] = useState(false)
  const [error, setError] = useState(null)

  const fetch = useCallback(async (overrideParams = {}) => {
    setLoading(true)
    setError(null)
    try {
      const res = await apiFn({ ...params, ...overrideParams })
      const resData = res.data?.data ?? res.data
      // Laravel paginator format
      if (resData?.data && resData?.meta) {
        setData(resData.data)
        setMeta(resData.meta)
      } else if (resData?.data) {
        setData(resData.data)
        setMeta({ current_page: resData.current_page, last_page: resData.last_page, total: resData.total })
      } else {
        setData(Array.isArray(resData) ? resData : [])
      }
      return resData
    } catch (err) {
      setError(err.response?.data?.message ?? 'Gagal memuat data.')
      return null
    } finally {
      setLoading(false)
    }
  }, [JSON.stringify(params)]) // eslint-disable-line react-hooks/exhaustive-deps

  useEffect(() => {
    fetch()
  }, [fetch])

  return { data, meta, loading, error, refetch: fetch }
}
